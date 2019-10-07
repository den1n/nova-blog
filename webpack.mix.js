let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .sass('resources/sass/fields.scss', '.')
    .js('resources/js/fields.js', '.')
    .sourceMaps()
    .version();
