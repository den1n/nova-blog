<?php

namespace Den1n\NovaBlog;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config' => config_path(),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/nova-blog'),
        ], 'lang');

        $this->publishes([
            __DIR__ . '/../resources/views/templates' => resource_path('views/vendor/nova-blog/templates'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../dist' => public_path('vendor/nova-blog'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-blog');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'nova-blog');
        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/nova-blog'));

        Route::macro('novaBlogRoutes', function (string $prefix = 'blog') {
            Route::model('post', config('nova-blog.models.post'));
            Route::model('category', config('nova-blog.models.category'));
            Route::model('tag', config('nova-blog.models.tag'));
            Route::group([
                'prefix' => $prefix,
                'middleware' => ['web'],
                'namespace' => '\\' . __NAMESPACE__,
            ], function () {
                $controller = '\\' . ltrim(config('nova-blog.controller.class'), '\\');
                Route::get('/{int?}', $controller . '@index')->where('int', '\d+')->name('nova-blog.index');
                Route::get('/search/{int?}', $controller . '@search')->name('nova-blog.search');
                Route::get('/author/{id}/{int?}', $controller . '@author')->name('nova-blog.author');
                Route::get('/category/{category}/{int?}', $controller . '@category')->name('nova-blog.category');
                Route::get('/tag/{tag}/{int?}', $controller . '@tag')->name('nova-blog.tag');
                Route::get('/{post}', $controller . '@show')->name('nova-blog.show');
            });
        });

        $models = config('nova-blog.models');
        $models['post']::observe(Observers\Post::class);
        $models['category']::observe(Observers\Category::class);
        $models['comment']::observe(Observers\Comment::class);
        $models['tag']::observe(Observers\Tag::class);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nova-blog.php', 'nova-blog');
    }
}
