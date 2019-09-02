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

// mix.js('resources/js/amcharts.js', 'public/js');
// mix.js('resources/js/app.js', 'public/js');
// mix.js('resources/js/bootstrap.js', 'public/js');
// mix.js('resources/js/*.js', 'public/js');

let fs = require('fs');

let getFiles = function (dir) {
    // get all 'files' in this directory
    // filter directories
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};

getFiles('resources/js').forEach(function (filepath) {
    mix.js('resources/js/' + filepath, 'public/js');
});

mix.sass('resources/sass/app.scss', 'public/css');
mix.sass('resources/sass/brackets.scss', 'public/css');

if (mix.inProduction()) mix.version();
