let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .sass('resources/sass/index.scss', '.')
    .js('resources/js/index.js', '.')
    .sourceMaps()
    .version();
