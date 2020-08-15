const plugin = require('tailwindcss/plugin')

module.exports = plugin(function({ addVariant, e }) {
  addVariant('focus-visible', ({ modifySelectors, separator }) => {
    modifySelectors(({ className }) => {
      return `.${e(`focus-visible${separator}${className}`)}[data-focus-visible-added]`
    })
  })
})
