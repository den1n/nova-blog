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
        $this->publishResources();
        $this->loadTranslations();
        $this->loadRoutes();

        $models = config('nova-blog.models');
        $models['post']::observe(Observers\Post::class);
        $models['category']::observe(Observers\Category::class);
        $models['comment']::observe(Observers\Comment::class);
        $models['tag']::observe(Observers\Tag::class);

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-blog-fields', __DIR__ . '/../dist/fields.js');
            Nova::style('nova-blog-fields', __DIR__ . '/../dist/fields.css');
        });
    }

    /**
     *  Publish package resources.
     */
    protected function publishResources(): void
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
    }

    /**
     *  Load package translation files.
     */
    protected function loadTranslations(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-blog');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'nova-blog');
        $this->loadJSONTranslationsFrom(__DIR__ . '/../resources/lang');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/nova-blog'));
    }

    /**
     *  Load package routes.
     */
    protected function loadRoutes(): void
    {
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
                Route::get('/', $controller . '@index')->where('int', '\d+')->name('nova-blog.index');
                Route::get('/search', $controller . '@search')->name('nova-blog.search');
                Route::get('/author/{id}', $controller . '@author')->name('nova-blog.author');
                Route::get('/category/{category}', $controller . '@category')->name('nova-blog.category');
                Route::get('/tag/{tag}', $controller . '@tag')->name('nova-blog.tag');
                Route::get('/{post}', $controller . '@show')->name('nova-blog.show');
            });
        });

        $this->app->booted(function() {
            if (!$this->app->routesAreCached()) {
                Route::group([
                    'prefix' => 'nova-vendor/den1n/nova-blog',
                    'middleware' => ['nova', 'api'],
                ], function () {
                    Route::get('/tags/field', TagsFieldController::class . '@index');
                });
            }
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nova-blog.php', 'nova-blog');
    }
}
