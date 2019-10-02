<?php

namespace Den1n\NovaBlog;

use Den1n\NovaBlog\Models\Category;
use Den1n\NovaBlog\Models\Post;
use Den1n\NovaBlog\Models\Tag;
use Illuminate\Contracts\Support\Renderable;
use Closure;

class BlogController extends \App\Http\Controllers\Controller
{
    protected $postsPerPage = 0;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->postsPerPage = config('nova-blog.controller.posts_per_page');
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
    public function search()
    {
        if (config('nova-blog.controller.allow_searching')) {
            $query = request()->input('query');
            $posts = config('nova-blog.models.post')::search($query)
                ->usingWebSearchQuery();

            return view('nova-blog::search', [
                'tags' => $this->sidebarTags(),
                'categories' => $this->sidebarCategories(),
                'anotherPosts' => $this->sidebarPosts(),
                'posts' => $posts->paginate($this->postsPerPage),
                'oldQuery' => $query,
            ]);
        } else
            abort(404);
    }

    /**
     * Show post.
     */
    public function show(Post $post): Renderable
    {
        if ($post->is_published or ($user = auth()->user() and $user->can('blogManager'))) {
            return view('nova-blog::templates.' . $post->template, [
                'anotherTags' => $this->sidebarTags(function($query) use ($post) {
                    return $query->excludeByPost($post->id);
                }),
                'anotherCategories' => $this->sidebarCategories(function($query) use ($post) {
                    return $query->exclude($post->category_id);
                }),
                'anotherPosts' => $this->sidebarPosts(function($query) use ($post) {
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
            'anotherPosts' => $this->sidebarPosts(function($query) use ($authorId) {
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
            'anotherCategories' => $this->sidebarCategories(function($query) use ($category) {
                return $query->exclude($category->id);
            }),
            'anotherPosts' => $this->sidebarPosts(function($query) use ($category) {
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
            'anotherTags' => $this->sidebarTags(function($query) use ($tag) {
                return $query->exclude($tag->id);
            }),
            'anotherPosts' => $this->sidebarPosts(function($query) use ($tag) {
                return $query->excludeByTag($tag->id);
            }),
            'tag' => $tag,
        ]);
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
