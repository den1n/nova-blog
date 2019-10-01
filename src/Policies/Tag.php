<?php

namespace Den1n\NovaBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Tag
{
    use HandlesAuthorization;

    public function before($user)
    {
        return $user->can('blogManager');
    }

    public function viewAny($user): bool
    {
        return $user->can('blogViewPosts') or $user->can('blogViewTags');
    }

    public function view($user): bool
    {
        return $user->can('blogViewPosts') or $user->can('blogViewTags');
    }

    public function create($user): bool
    {
        return $user->can('blogCreateTags');
    }

    public function update($user): bool
    {
        return $user->can('blogUpdateTags');
    }

    public function delete($user): bool
    {
        return $user->can('blogDeleteTags');
    }

    public function restore($user): bool
    {
        return $user->can('blogDeleteTags');
    }

    public function forceDelete($user): bool
    {
        return $user->can('blogDeleteTags');
    }
}
