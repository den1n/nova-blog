<?php

return [

    /**
     * Names of blog models used by application.
     */

    'models' => [
        'post' => \Den1n\NovaBlog\Post::class,
        'category' => \Den1n\NovaBlog\Category::class,
        'comment' => \Den1n\NovaBlog\Comment::class,
        'tag' => \Den1n\NovaBlog\Tag::class,
        'user' => config('auth.providers.users.model', \App\User::class),
    ],

    /**
     * Names of blog resources used by Nova.
     */

    'resources' => [
        'post' => \Den1n\NovaBlog\PostResource::class,
        'category' => \Den1n\NovaBlog\CategoryResource::class,
        'comment' => \Den1n\NovaBlog\CommentResource::class,
        'tag' => \Den1n\NovaBlog\TagResource::class,
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
         * Number of posts displayed on one page.
         */

        'posts_per_page' => 20,

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

        'tags_on_sidebar' => 50,

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
