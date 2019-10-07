# nova-blog

Laravel Nova blog resources.

## Installation

Require package with Composer.

```sh
composer require den1n/nova-blog
```

Publish package resources.

```sh
php artisan vendor:publish --provider=Den1n\NovaBlog\ServiceProvider
```

This will publish the following resources:

* Configuration file `config/nova-blog.php`
* Migration file `database/migrations/*_create_blog_tables.php`
* Translations `resources/lang/vendor/nova-blog`
* Views `resources/views/vendor/nova-blog`

Add `Vue` components provided by the package to file `resources\js\app.js` before `Vue` initialization.

```js
require('../../vendor/den1n/nova-blog/resources/js');
```

Add `CSS` styles provided by the package to file `resources\sass\app.scss`.

```scss
@import '../../vendor/den1n/nova-blog/resources/sass';
```

Migrate database.

```sh
php artisan migrate
```

Add instance of class `Den1n\NovaBlog\Tool` to your `App\Providers\NovaServiceProvider::tools()` method to display the blog posts, comments, categories and tags within your Nova resources.

```php
/**
 * Get the tools that should be listed in the Nova sidebar.
 *
 * @return array
 */
public function tools()
{
    return [
        new \Den1n\NovaBlog\Tool,
    ];
}
```

## Serving Blog Posts

To serve blog posts append this route to your `routes/web.php` file.

```php
Route::novaBlogRoutes();
```

You can define route with prefix.

```php
Route::novaBlogRoutes('/blog');
```

You can get url to existing post by using Laravel `route` helper.

```php
use \Den1n\NovaPosts\Models\Post;

$url = route('nova-blog.post', [
    'post' => Post::find(1),
]);

// Or you can pass a post slug.
$url = route('nova-blog.post', [
    'post' => 'my-post-slug',
]);
```

## Default template

Blog controller will serve posts with `default` template.

Template is published to views directory `resources/views/vendor/nova-blog/templates/default.blade.php`.

Template will receive these variables when processed:

* $post: instance of `Post` model.
* $sidebarPosts: collection of `Post` models.
* $sidebarCategories: collection of `Category` models.
* $sidebarTags: collection of `Tag` models.

You can freely modify `default` template.

## Creating a custom template

First create a custom blade template in `resources/views/vendor/nova-blog/templates` directory.

For example, `rich.blade.php`.

Then register it in configuration file `config/nova-blog.php`.

```php
/**
 * Array of templates used by controller.
 */

'templates' => [
    // ...
    [
        'name' => 'rich',
        'description' => 'A rich template',
    ],
],
```

After that your custom template will be available to select when creating blog post or updating existing one.

## Screenshots

### Posts

![Posts](https://raw.githubusercontent.com/den1n/nova-blog/master/screens/posts.png)

### Post Form

![Post Form](https://raw.githubusercontent.com/den1n/nova-blog/master/screens/post-form.png)

### Post Detail

![Post Detail](https://raw.githubusercontent.com/den1n/nova-blog/master/screens/post-detail.png)

### Blog Example

![Blog Example](https://raw.githubusercontent.com/den1n/nova-blog/master/screens/blog.png)

## Contributing

1. Fork it.
2. Create your feature branch: `git checkout -b my-new-feature`.
3. Commit your changes: `git commit -am 'Add some feature'`.
4. Push to the branch: `git push origin my-new-feature`.
5. Submit a pull request.

## Support

If you require any support open an issue on this repository.

## License

MIT
