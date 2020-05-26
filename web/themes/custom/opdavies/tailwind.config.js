const {variants} = require('tailwindcss/defaultConfig')
const {fontFamily, spacing} = require('tailwindcss/defaultTheme')

module.exports = {
  important: true,
  purge: {
    content: [
      'templates/**/*.twig',

      // Include any classes used within config (e.g. within views).
      '../../../../config/**/*.yml',

      // Include any classes stored within body fields.
      'body-field-values.txt'
    ],
    options: {
      whitelist: [],
    }
  },
  theme: {
    extend: {
      colors: {
        inherit: 'inherit'
      },
      fontFamily: {
        mono: [
          'Operator Mono',
          'Roboto Mono',
          ...fontFamily.mono
        ]
      },
      spacing: {
        '2px': '2px'
      },
      borderWidth: {
        3: '3px'
      },
      width: {
        96: '24rem'
      }
    }
  },
  corePlugins: {
    container: false
  },
  plugins: [
    // require('tailwindcss-skip-link')()
  ],
  variants: {
    borderStyle: [...variants.borderStyle, 'hover', 'focus'],
    borderWidth: [...variants.borderWidth, 'hover', 'focus'],
    margin: [...variants.margin, 'first', 'last', 'odd', 'even'],
  }
}
