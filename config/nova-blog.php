<?php

return [

    /**
     * Names of blog models used by application.
     */

    'models' => [
        'post' => \Den1n\NovaBlog\Models\Post::class,
        'category' => \Den1n\NovaBlog\Models\Category::class,
        'comment' => \Den1n\NovaBlog\Models\Comment::class,
        'tag' => \Den1n\NovaBlog\Models\Tag::class,
        'user' => config('auth.providers.users.model', \App\User::class),
    ],

    /**
     * Names of blog resources used by Nova.
     */

    'resources' => [
        'post' => \Den1n\NovaBlog\Resources\Post::class,
        'category' => \Den1n\NovaBlog\Resources\Category::class,
        'comment' => \Den1n\NovaBlog\Resources\Comment::class,
        'tag' => \Den1n\NovaBlog\Resources\Tag::class,
        'user' => \App\Nova\User::class,
    ],

    /**
     * Settings of Nova field used for editing posts content.
     */

    'editor' => [
        /**
         * Name of Nova field class used for editing of posts content.
         */

        'class' => \Laravel\Nova\Fields\Trix::class,

        /**
         * Options which will be applied to te field instance.
         * Key: name of field method.
         * Value: list of method arguments.
         */

        'options' => [
            // 'withFiles' => ['public'],
        ],
    ],

    /**
     * Names of database tables used by blog models.
     */

    'tables' => [
        'posts' => 'blog_posts',
        'post_tags' => 'blog_post_tags',
        'categories' => 'blog_categories',
        'comments' => 'blog_comments',
        'tags' => 'blog_tags',
        'users' => 'users',
    ],

    /**
     * Settings of blog controller.
     */

    'controller' => [
        /**
         * Controller class which will be serving blog.
         */

        'class' => \Den1n\NovaBlog\BlogController::class,

        /**
         * Allow readers to search for posts.
         */
        'allow_searching' => true,

        /**
         * Allow readers to comment on posts.
         */
        'allow_commenting' => false,

        /**
         * Number of posts displayed on one page.
         */

        'posts_per_page' => 15,

        /**
         * Number of posts displayed on one page.
         */

        'comments_per_page' => 100,

        /**
         * Number of posts displayed on sidebar.
         */

        'posts_on_sidebar' => 5,

        /**
         * Number of categories displayed on sidebar.
         */

        'categories_on_sidebar' => 10,

        /**
         * Number of tags displayed on sidebar.
         */

        'tags_on_sidebar' => 25,

        /**
         * Array of templates used by controller.
         */

        'templates' => [
            [
                'name' => 'default',
                'description' => 'Default',
            ],
        ],
    ],
];
