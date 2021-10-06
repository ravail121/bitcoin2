let mix = require('laravel-mix');
const webpack = require('webpack');
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

mix.copyDirectory('resources/assets/images', 'public/images')
  .js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/front/front.scss', 'public/css')
  .sass('resources/assets/sass/admin/admin.scss', 'public/css');
