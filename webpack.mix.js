let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .sass('resources/sass/fields.scss', '.')
    .sass('resources/sass/index.scss', '.')
    .js('resources/js/fields.js', '.')
    .js('resources/js/index.js', '.')
    .sourceMaps()
    .version();
