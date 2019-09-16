<?php

namespace Den1n\NovaBlog;

use App\AccountData;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\DateTime;

class PublishAction extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Get the displayable name of the action.
     */
    public function name(): string
    {
        return __('Publish');
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(): array
    {
        return [
            DateTime::make(__('Date'), 'date'),
        ];
    }

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): array
    {
        foreach ($models as $model) {
            $model->published_at = $fields->date;
            $model->save();
        }
        return Action::message(__('Posts are published!'));
    }
}
