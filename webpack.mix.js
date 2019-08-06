const mix = require('laravel-mix');

mix.babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/amcharts.js', 'public/js');
// mix.js('resources/js/app.js', 'public/js');
mix.js('resources/js/bootstrap.js', 'public/js');
// mix.js('resources/js/common.js', 'public/js');

mix.sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) mix.version();