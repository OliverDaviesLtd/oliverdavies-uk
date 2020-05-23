let mix = require('laravel-mix');

mix.postCss('src/tailwind.pcss', 'dist', [
  require('postcss-import'),
  require('tailwindcss'),
  require('postcss-nested'),
  require('autoprefixer')
])

mix.js('src/app.js', 'dist/js')
