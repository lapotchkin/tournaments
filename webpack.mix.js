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

mix.js('resources/js/amcharts.js', 'public/js').sourceMaps();
mix.js('resources/js/app.js', 'public/js').sourceMaps();
mix.js('resources/js/bootstrap.js', 'public/js').sourceMaps();
mix.js('resources/js/gameFormModule.js', 'public/js').sourceMaps();
mix.js('resources/js/playoffModule.js', 'public/js').sourceMaps();
mix.js('resources/js/teamManagerModule.js', 'public/js').sourceMaps();

mix.sass('resources/sass/app.scss', 'public/css');
mix.sass('resources/sass/brackets.scss', 'public/css');

if (mix.inProduction()) mix.version();
