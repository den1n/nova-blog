<?php

namespace Den1n\NovaBlog;

use Carbon\Carbon;
use Closure;
use Den1n\NovaBlog\Models\Category;
use Den1n\NovaBlog\Models\Post;
use Den1n\NovaBlog\Models\Tag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class BlogController extends \App\Http\Controllers\Controller
{
    protected $postsPerPage = 0;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $controller = config('nova-blog.controller');
        session()->remove($controller['redirect_key']);
        $this->postsPerPage = $controller['posts_per_page'];
    }

    /**
     * Show all posts.
     */
    public function index(): Renderable
    {
        return view('nova-blog::index', [
            'tags' => $this->sidebarTags(),
            'categories' => $this->sidebarCategories(),
            'posts' => $this->recentPosts(),
        ]);
    }

    /**
     * Show all posts filtered by search query.
     */
    public function search(Request $request)
    {
        if (config('nova-blog.controller.allow_searching')) {
            $query = $request->input('query');
            $posts = config('nova-blog.models.post')::search($query)
                ->orderBy('published_at', 'desc')
                ->usingWebSearchQuery();

            return view('nova-blog::search', [
                'tags' => $this->sidebarTags(),
                'categories' => $this->sidebarCategories(),
                'sidebarPosts' => $this->sidebarPosts(),
                'posts' => $posts->paginate($this->postsPerPage),
                'oldQuery' => $query,
            ]);
        } else
            abort(404);
    }

    /**
     * Show post.
     */
    public function post(Post $post): Renderable
    {
        if ($post->is_published or ($user = auth()->user() and $user->can('blogManager'))) {
            return view('nova-blog::templates.' . $post->template, [
                'sidebarTags' => $this->sidebarTags(function($query) use ($post) {
                    return $query->excludeByPost($post->id);
                }),
                'sidebarCategories' => $this->sidebarCategories(function($query) use ($post) {
                    return $query->exclude($post->category_id);
                }),
                'sidebarPosts' => $this->sidebarPosts(function($query) use ($post) {
                    return $query->exclude($post->id);
                }),
                'post' => $post,
            ]);
        } else
            abort(404);
    }

    /**
     * Show posts by author.
     */
    public function author(int $authorId): Renderable
    {
        return view('nova-blog::author', [
            'tags' => $this->sidebarTags(),
            'categories' => $this->sidebarCategories(),
            'author' => config('nova-blog.models.user')::find($authorId),
            'sidebarPosts' => $this->sidebarPosts(function($query) use ($authorId) {
                return $query->excludeByAuthor($authorId);
            }),
            'posts' => $this->recentPosts(function($query) use ($authorId) {
                return $query->author($authorId);
            }),
        ]);
    }

    /**
     * Show posts by category.
     */
    public function category(Category $category): Renderable
    {
        return view('nova-blog::category', [
            'tags' => $this->sidebarTags(),
            'posts' => $category->posts()->recent()->paginate($this->postsPerPage),
            'sidebarCategories' => $this->sidebarCategories(function($query) use ($category) {
                return $query->exclude($category->id);
            }),
            'sidebarPosts' => $this->sidebarPosts(function($query) use ($category) {
                return $query->excludeByCategory($category->id);
            }),
            'category' => $category,
        ]);
    }

    /**
     * Show posts by tag.
     */
    public function tag(Tag $tag): Renderable
    {
        return view('nova-blog::tag', [
            'categories' => $this->sidebarCategories(),
            'posts' => $tag->posts()->recent()->paginate($this->postsPerPage),
            'sidebarTags' => $this->sidebarTags(function($query) use ($tag) {
                return $query->exclude($tag->id);
            }),
            'sidebarPosts' => $this->sidebarPosts(function($query) use ($tag) {
                return $query->excludeByTag($tag->id);
            }),
            'tag' => $tag,
        ]);
    }

    /**
     * Redirect user to login page.
     */
    public function login(Request $request): RedirectResponse
    {
        $controller = config('nova-blog.controller');
        if ($next = $request->input('next'))
            session()->put($controller['redirect_key'], $next);
        return redirect()->route($controller['login_route']);
    }

    /**
     * Get all comments for a post.
     */
    public function comments(Request $request): Collection
    {
        if ($post = config('nova-blog.models.post')::find($request->input('post_id'))) {
            return $post->comments;
        } else
            throw new InvalidArgumentException('Invalid post');
    }

    /**
     * Create comment.
     */
    public function commentsCreate(Request $request): Models\Comment
    {
        $models = config('nova-blog.models');
        if ($post = $models['post']::find($request->input('post_id'))) {
            if ($content = trim($request->content)) {
                $comment = new $models['comment'];
                $comment->content = $content;
                $comment->post_id = $post->id;
                $comment->save();
                return $comment->load('author');
            } else
                throw new InvalidArgumentException('Empty content');
        } else
            throw new InvalidArgumentException('Invalid post');
    }

    /**
     * Update comment.
     */
    public function commentsUpdate(Request $request): Models\Comment
    {
        if ($comment = config('nova-blog.models.comment')::find($request->input('comment_id'))) {
            if ($content = trim($request->content)) {
                $comment->content = $content;
                $comment->save();
                return $comment;
            } else
                throw new InvalidArgumentException('Empty content');
        } else
            throw new InvalidArgumentException('Invalid comment');
    }

    /**
     * Remove comment.
     */
    public function commentsRemove(Request $request): Models\Comment
    {
        if ($comment = config('nova-blog.models.comment')::find($request->input('comment_id'))) {
            $comment->delete();
            return $comment;
        } else
            throw new InvalidArgumentException('Invalid comment');
    }

    /**
     * Query list of recent posts.
     */
    protected function recentPosts(Closure $filter = null): iterable
    {
        $filter = $filter ?: function($query) { return $query; };
        $query = config('nova-blog.models.post')::recent();
        return $filter($query)->paginate($this->postsPerPage);
    }

    /**
     * Query list of posts for sidebar.
     */
    protected function sidebarPosts(Closure $filter = null): iterable
    {
        if ($count = config('nova-blog.controller.posts_on_sidebar')) {
            $filter = $filter ?: function($query) { return $query; };
            $query = config('nova-blog.models.post')::recent()->limit($count);
            return $filter($query)->get();
        } else
            return [];
    }

    /**
     * Query list of categories for sidebar.
     */
    protected function sidebarCategories(Closure $filter = null): iterable
    {
        if ($count = config('nova-blog.controller.categories_on_sidebar')) {
            $filter = $filter ?: function($query) { return $query; };
            $query = config('nova-blog.models.category')::orderBy('name')->limit($count);
            return $filter($query)->get();
        } else
            return [];
    }

    /**
     * Query list of popular tags for sidebar.
     */
    protected function sidebarTags(Closure $filter = null): iterable
    {
        if ($count = config('nova-blog.controller.tags_on_sidebar')) {
            $filter = $filter ?: function($query) { return $query; };
            $query = config('nova-blog.models.tag')::withCount('posts')
                ->orderBy('posts_count')->limit($count);
            return $filter($query)->get();
        } else
            return [];
    }
}
