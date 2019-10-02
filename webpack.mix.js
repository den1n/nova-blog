let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .sass('resources/sass/index.scss', '.')
    .sass('resources/sass/fields.scss', '.')
    .js('resources/js/index.js', '.')
    .js('resources/js/fields.js', '.')
    .sourceMaps()
    .version();
