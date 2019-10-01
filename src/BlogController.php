<?php

namespace Den1n\NovaBlog;

use Den1n\NovaBlog\Models\Category;
use Den1n\NovaBlog\Models\Post;
use Den1n\NovaBlog\Models\Tag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;

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
            'categories' => $this->sidebarCategories()->cursor(),
            'posts' => $this->recentPosts()->paginate($this->postsPerPage),
            'tags' => $this->sidebarTags()->cursor(),
        ]);
    }

    /**
     * Show all posts filtered by search query.
     */
    public function search(): Renderable
    {
        if ($query = request()->input('query')) {
            $posts = config('nova-blog.models.post')::search($query)
                ->usingWebSearchQuery();

            return view('nova-blog::search', [
                'categories' => $this->sidebarCategories()->cursor(),
                'anotherPosts' => $this->sidebarPosts()->cursor(),
                'posts' => $posts->paginate($this->postsPerPage),
                'tags' => $this->sidebarTags()->cursor(),
                'oldQuery' => $query,
            ]);
        } else
            return redirect()->back();
    }

    /**
     * Show post.
     */
    public function show(Post $post): Renderable
    {
        if ($post->is_published or ($user = auth()->user() and $user->can('blogManager'))) {
            return view('nova-blog::templates.' . $post->template, [
                'anotherCategories' => $this->sidebarCategories()->exclude($post->category_id)->cursor(),
                'anotherPosts' => $this->sidebarPosts()->exclude($post->id)->cursor(),
                'anotherTags' => $this->sidebarTags()->excludeByPost($post->id)->cursor(),
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
            'author' => config('nova-blog.models.user')::find($authorId),
            'anotherPosts' => $this->sidebarPosts()->excludeByAuthor($authorId)->cursor(),
            'posts' => $this->recentPosts()->author($authorId)->paginate($this->postsPerPage),
            'categories' => $this->sidebarCategories()->cursor(),
            'tags' => $this->sidebarTags()->cursor(),
        ]);
    }

    /**
     * Show posts by category.
     */
    public function category(Category $category): Renderable
    {
        return view('nova-blog::category', [
            'anotherCategories' => $this->sidebarCategories()->exclude($category->id)->cursor(),
            'anotherPosts' => $this->sidebarPosts()->excludeByCategory($category->id)->cursor(),
            'posts' => $category->posts()->recent()->paginate($this->postsPerPage),
            'tags' => $this->sidebarTags()->cursor(),
            'category' => $category,
        ]);
    }

    /**
     * Show posts by tag.
     */
    public function tag(Tag $tag): Renderable
    {
        return view('nova-blog::tag', [
            'categories' => $this->sidebarCategories()->cursor(),
            'anotherPosts' => $this->sidebarPosts()->excludeByTag($tag->id)->cursor(),
            'anotherTags' => $this->sidebarTags()->exclude($tag->id)->cursor(),
            'posts' => $tag->posts()->recent()->paginate($this->postsPerPage),
            'tag' => $tag,
        ]);
    }

    /**
     * Query list of recent posts.
     */
    protected function recentPosts(): Builder
    {
        return config('nova-blog.models.post')::recent();
    }

    /**
     * Query list of posts for sidebar.
     */
    protected function sidebarPosts(): Builder
    {
        return config('nova-blog.models.post')::recent()
            ->take(config('nova-blog.controller.posts_on_sidebar'));
    }

    /**
     * Query list of categories for sidebar.
     */
    protected function sidebarCategories(): Builder
    {
        return config('nova-blog.models.category')::orderBy('name')
            ->take(config('nova-blog.controller.categories_on_sidebar'));
    }

    /**
     * Query list of popular tags for sidebar.
     */
    protected function sidebarTags(): Builder
    {
        return config('nova-blog.models.tag')::has('posts')->withCount('posts')->orderBy('posts_count')
            ->take(config('nova-blog.controller.tags_on_sidebar'));
    }
}
