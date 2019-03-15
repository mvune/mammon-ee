const mix = require('laravel-mix');

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

mix.js('resources/js/main.js', 'public/js')
   .js('resources/js/bootstrap.js', 'public/js')
   .sass('resources/sass/main.scss', 'public/css')
   .copyDirectory('resources/images', 'public/images');

mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js/')
    }
  }
});

if (mix.inProduction())
  mix.version()
else
  mix.sourceMaps()
