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
        $this->loadMigrations();
        $this->loadTranslations();
        $this->loadRoutes();
        $this->loadViews();
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
            __DIR__ . '/../resources/js/frontend' => resource_path('js/vendor/nova-blog'),
        ], 'assets');

        $this->publishes([
            __DIR__ . '/../resources/sass/frontend.scss' => resource_path('sass/vendor/nova-blog/index.scss'),
        ], 'assets');
    }

    /**
     * Load package migrations files.
     */
    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    /**
     *  Load package translation files.
     */
    protected function loadTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'nova-blog');
        $this->loadJSONTranslationsFrom(__DIR__ . '/../resources/lang');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/nova-blog'));
    }

    /**
     *  Load package routes.
     */
    protected function loadRoutes(): void
    {
        $controller = '\\' . ltrim(config('nova-blog.controller.class'), '\\');

        Route::macro('novaBlogRoutes', function (string $prefix = 'blog') use ($controller) {
            Route::model('post', config('nova-blog.models.post'));
            Route::model('category', config('nova-blog.models.category'));
            Route::model('tag', config('nova-blog.models.tag'));

            Route::group([
                'prefix' => $prefix,
                'namespace' => '\\' . __NAMESPACE__,
                'middleware' => ['web'],
            ], function () use ($controller) {
                Route::get('/', $controller . '@index')->where('int', '\d+')->name('nova-blog.index');
                Route::get('/search', $controller . '@search')->name('nova-blog.search');
                Route::get('/author/{id}', $controller . '@author')->name('nova-blog.author');
                Route::get('/category/{category}', $controller . '@category')->name('nova-blog.category');
                Route::get('/tag/{tag}', $controller . '@tag')->name('nova-blog.tag');
                Route::get('/login', $controller . '@login')->name('nova-blog.login');
                Route::get('/{post}', $controller . '@post')->name('nova-blog.post');
            });
        });

        Route::group([
            'prefix' => '/vendor/nova-blog',
            'namespace' => '\\' . __NAMESPACE__,
            'middleware' => ['web'],
        ], function () use ($controller) {
            Route::get('/comments', $controller . '@comments')->name('nova-blog.comments');
            Route::put('/comments', $controller . '@commentsCreate')->name('nova-blog.comments.create');
            Route::post('/comments', $controller . '@commentsUpdate')->name('nova-blog.comments.update');
            Route::delete('/comments', $controller . '@commentsRemove')->name('nova-blog.comments.remove');
        });

        $this->app->booted(function() {
            if (!$this->app->routesAreCached()) {
                Route::group([
                    'prefix' => 'nova-vendor/den1n/nova-blog',
                    'middleware' => ['nova', 'api'],
                ], function () {
                    Route::get('/tags', TagsFieldController::class . '@index');
                });
            }
        });
    }

    /**
     *  Load package views.
     */
    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-blog');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nova-blog.php', 'nova-blog');
    }
}
