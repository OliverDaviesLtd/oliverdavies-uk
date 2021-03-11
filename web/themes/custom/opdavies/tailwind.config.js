const colors = require('./tailwind-colours')
const defaultConfig = require('tailwindcss/defaultConfig')
const defaultTheme = require('tailwindcss/defaultTheme')
const focusVisible = require('./tailwind-plugin-focus-visible')
const forms = require('@tailwindcss/forms')
const typography = require('@tailwindcss/typography')
const { fontFamily } = defaultTheme
const { variants } = defaultConfig

module.exports = {
  important: true,
  purge: {
    content: [
      '../../../../config/**/*.yml',
      'opdavies.theme',
      'tailwind-safelist-classes.txt',
      'templates/**/*.twig',
    ],
  },
  theme: {
    extend: {
      typography: (theme) => ({
        DEFAULT: {
          css: {
            a: {
              color: theme('colors.blue.700')
            },
            code: {
              backgroundColor: theme('colors.gray.150'),
              fontWeight: theme('fontWeight.normal'),
              paddingBottom: theme('spacing.px'),
              paddingLeft: theme('spacing.1'),
              paddingRight: theme('spacing.1'),
              paddingTop: theme('spacing.px')
            },
            h2: {
              marginBottom: theme('spacing.2'),
              marginTop: theme('spacing.8')
            },
            pre: {
              backgroundColor: theme('colors.gray.150'),
              borderRadius: '0',
              color: theme('colors.gray.800'),
              padding: theme('spacing.6')
            },
            'code::before': false,
            'code::after': false,
            'pre code::before': false,
            'pre code::after': false
          }
        }
      }),
      colors,
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
    focusVisible,
    forms,
    typography
  ],
  variants: {
    borderColor: [...variants.borderColor, 'focus-visible'],
    borderStyle: [...variants.borderStyle, 'hover', 'focus'],
    borderWidth: [...variants.borderWidth, 'hover', 'focus'],
    margin: [...variants.margin, 'first', 'last', 'odd', 'even'],
    typography: ['responsive']
  }
}
