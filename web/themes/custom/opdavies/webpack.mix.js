let mix = require('laravel-mix');

// require('laravel-mix-purgecss')

mix.postCss('src/tailwind.pcss', 'dist', [
  require('postcss-import'),
  require('tailwindcss'),
  require('postcss-nested'),
  require('autoprefixer')
])

// mix.purgeCss({
//   paths: () => glob.sync([
//     path.join(__dirname, '**/*.twig')
//   ]),
//   whitelistPatterns: [],
//   whitelistPatternsChildren: []
// })
