<?php

namespace Den1n\NovaBlog;

use Illuminate\Contracts\Support\Renderable;

class BlogController extends \App\Http\Controllers\Controller
{
    protected $postsPerPage = 0;
    protected $postsOnSidebar = 0;
    protected $categoriesOnSidebar = 0;
    protected $tagsOnSidebar = 0;

    /**
     * Instantiate a blog controller.
     */
    public function __construct()
    {
        $this->postsPerPage = config('nova-blog.controller.posts_per_page');
        $this->postsOnSidebar = config('nova-blog.controller.posts_on_sidebar');
        $this->categoriesOnSidebar = config('nova-blog.controller.categories_on_sidebar');
        $this->tagsOnSidebar = config('nova-blog.controller.tags_on_sidebar');
    }

    /**
     * Show all posts.
     */
    public function index(int $page = 0): Renderable
    {
        return view('nova-blog::index', [
            'categories' => $this->getCategories()->cursor(),
            'posts' => $this->getRecentPosts($page)->cursor(),
            'tags' => $this->getTags()->cursor(),
        ]);
    }

    /**
     * Show all posts filtered by search query.
     */
    public function search(int $page = 0)
    {
        if ($search = request()->q) {
            return view('nova-blog::search', [
                'categories' => $this->getCategories()->cursor(),
                'anotherPosts' => $this->getSidebarPosts()->cursor(),
                'posts' => $this->getRecentPosts($page)->search($search)->cursor(),
                'tags' => $this->getTags()->cursor(),
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
                'anotherCategories' => $this->getCategories()->exclude($post->category_id)->cursor(),
                'anotherPosts' => $this->getSidebarPosts()->exclude($post->id)->cursor(),
                'anotherTags' => $this->getTags()->excludeByPost($post->id)->cursor(),
                'post' => $post,
            ]);
        } else
            abort(404);
    }

    /**
     * Show posts by author.
     */
    public function author(int $authorId, int $page = 0): Renderable
    {
        return view('nova-blog::author', [
            'author' => config('nova-blog.models.user')::find($authorId),
            'anotherPosts' => $this->getSidebarPosts()->where('author_id', '!=', $authorId)->cursor(),
            'posts' => $this->getRecentPosts($page)->where('author_id', $authorId)->cursor(),
            'categories' => $this->getCategories()->cursor(),
            'tags' => $this->getTags()->cursor(),
        ]);
    }

    /**
     * Show posts by category.
     */
    public function category(Category $category, int $page = 0): Renderable
    {
        return view('nova-blog::category', [
            'anotherCategories' => $this->getCategories()->exclude($category->id)->cursor(),
            'anotherPosts' => $this->getSidebarPosts()->excludeByCategory($category->id)->cursor(),
            'posts' => $category->posts()->recent($page, $this->postsPerPage)->cursor(),
            'tags' => $this->getTags()->cursor(),
            'category' => $category,
        ]);
    }

    /**
     * Show posts by tag.
     */
    public function tag(Tag $tag, int $page = 0): Renderable
    {
        return view('nova-blog::tag', [
            'categories' => $this->getCategories()->cursor(),
            'anotherPosts' => $this->getSidebarPosts()->excludeByTag($tag->id)->cursor(),
            'anotherTags' => $this->getTags()->exclude($tag->id)->cursor(),
            'posts' => $tag->posts()->recent($page, $this->postsPerPage)->cursor(),
            'tag' => $tag,
        ]);
    }

    protected function getRecentPosts(int $page = 0)
    {
        return config('nova-blog.models.post')::recent($page, $this->postsPerPage);
    }

    protected function getSidebarPosts()
    {
        return config('nova-blog.models.post')::recent(0, $this->postsOnSidebar);
    }

    protected function getCategories()
    {
        return config('nova-blog.models.category')::orderBy('name')
            ->take($this->categoriesOnSidebar);
    }

    protected function getTags(int ...$ignoredTags)
    {
        return config('nova-blog.models.tag')::has('posts')
            ->withCount('posts')
            ->orderBy('posts_count')
            ->take($this->tagsOnSidebar);
    }
}
