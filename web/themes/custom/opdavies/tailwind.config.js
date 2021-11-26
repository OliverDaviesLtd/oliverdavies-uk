const colors = require('./tailwindcss/colours')
const defaultTheme = require('tailwindcss/defaultTheme')
const { fontFamily } = defaultTheme

module.exports = {
  mode: 'jit',
  darkMode: 'media',
  important: true,
  purge: {
    content: [
      'templates/**/*.html.twig',
      'tools/tailwindcss/safelist-classes.txt'
    ],
  },
  theme: {
    outline: {
      black: '1px solid black',
      white: '1px solid white'
    },
    extend: {
      colors,
      fontFamily: {
        sans: [
          'Roboto Condensed',
          'Arial',
          'Helvetica Neue',
          'Helvetica',
          'sans-serif',
        ],
        mono: [
          'ui-monospace',
          'SFMono-Regular',
          'SF Mono',
          'Consolas',
          'Liberation Mono',
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
        18: '4.5rem',
        96: '24rem'
      }
    }
  },
  corePlugins: {
    container: false
  },
  plugins: [
    require('./tailwindcss/plugins/focus-visible'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ]
}
