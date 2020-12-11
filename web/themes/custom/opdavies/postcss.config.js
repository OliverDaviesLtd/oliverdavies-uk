module.exports = {
  plugins: [
    require('postcss-easy-import')({
      extensions: ['.css', '.pcss']
    }),
    require('tailwindcss'),
    require('postcss-nested'),
    require('autoprefixer')
  ]
}
