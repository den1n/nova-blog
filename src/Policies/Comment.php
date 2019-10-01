<?php

namespace Den1n\NovaBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use \Den1n\NovaBlog\Models\Comment as Model;

class Comment
{
    use HandlesAuthorization;

    public function before($user)
    {
        return $user->can('blogManager');
    }

    public function viewAny($user): bool
    {
        return $user->can('blogViewComments');
    }

    public function view($user, Model $comment): bool
    {
        return $user->can('blogViewComments');
    }

    public function create($user): bool
    {
        return $user->can('blogCreateComments');
    }

    public function update($user, Model $comment): bool
    {
        return $user->can('blogUpdateComments') and $user->id == $comment->author_id;
    }

    public function delete($user, Model $comment): bool
    {
        return $user->can('blogDeleteComments') and $user->id == $comment->author_id;
    }

    public function restore($user, Model $comment): bool
    {
        return $user->can('blogDeleteComments') and $user->id == $comment->author_id;
    }

    public function forceDelete($user, Model $comment): bool
    {
        return $user->can('blogDeleteComments') and $user->id == $comment->author_id;
    }
}
