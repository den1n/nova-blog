<?php

namespace Den1n\NovaBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Category
{
    use HandlesAuthorization;

    public function before($user)
    {
        return $user->can('blogManager');
    }

    public function viewAny($user): bool
    {
        return $user->can('blogViewPosts') or $user->can('blogViewCategories');
    }

    public function view($user): bool
    {
        return $user->can('blogViewPosts') or $user->can('blogViewCategories');
    }

    public function create($user): bool
    {
        return $user->can('blogCreateCategories');
    }

    public function update($user): bool
    {
        return $user->can('blogUpdateCategories');
    }

    public function delete($user): bool
    {
        return $user->can('blogDeleteCategories');
    }

    public function restore($user): bool
    {
        return $user->can('blogDeleteCategories');
    }

    public function forceDelete($user): bool
    {
        return $user->can('blogDeleteCategories');
    }
}
