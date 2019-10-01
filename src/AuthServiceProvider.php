<?php

namespace Den1n\NovaBlog;

use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{
    protected $permissions = [
        'blogManager',
        'blogViewPosts',
        'blogCreatePosts',
        'blogUpdatePosts',
        'blogDeletePosts',
        'blogViewComments',
        'blogCreateComments',
        'blogUpdateComments',
        'blogDeleteComments',
        'blogViewCategories',
        'blogCreateCategories',
        'blogUpdateCategories',
        'blogDeleteCategories',
        'blogViewTags',
        'blogCreateTags',
        'blogUpdateTags',
        'blogDeleteTags',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $models = config('nova-blog.models');
        $this->policies = [
            $models['post'] => Policies\Post::class,
            $models['comment'] => Policies\Comment::class,
            $models['category'] => Policies\Category::class,
            $models['tag'] => Policies\Tag::class,
        ];

        $this->registerPolicies();

        foreach ($this->permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                if (class_uses($user)['Den1n\\Permissions\\HasRoles'] ?? false) {
                    return $user->roles->contains(function ($role) use ($permission) {
                        return $role->super or in_array($permission, $role->permissions);
                    });
                } else
                    return true;
            });
        }
    }
}
