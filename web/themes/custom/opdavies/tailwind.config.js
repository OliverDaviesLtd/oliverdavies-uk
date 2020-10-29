const defaultConfig = require('tailwindcss/defaultConfig')
const defaultTheme = require('tailwindcss/defaultTheme')
const focusVisible = require('./tailwind-focus-visible')
const typography = require('@tailwindcss/typography')
const { fontFamily } = defaultTheme
const { variants } = defaultConfig

module.exports = {
  important: true,
  purge: {
    mode: 'layers',
    content: [
      'templates/**/*.twig',

      // Include any classes used within config (e.g. within views).
      '../../../../config/**/*.yml',

      // Include any classes stored within body fields.
      'body-field-values.txt'
    ],
  },
  theme: {
    typography: (theme) => ({
      default: {
        css: {
          a: {
            color: theme('colors.blue.500')
          },
          code: {
            backgroundColor: theme('colors.gray.200'),
            borderColor: theme('colors.gray.400'),
            borderStyle: 'solid',
            borderWidth: '1px',
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
            backgroundColor: theme('colors.gray.200'),
            borderLeftColor: theme('colors.gray.400'),
            borderLeftStyle: 'solid',
            borderLeftWidth: '3px',
            borderRadius: '0',
            color: theme('colors.gray.800'),
            padding: theme('spacing.6')
          },
          'code::before': false,
          'code::after': false,
          'pre code::before': false,
          'pre code::after': false
        }
      },
      dark: {
        css: {
          color: theme('colors.gray.200'),

          h2: {
            color: theme('colors.white')
          },

          strong: {
            color: theme('colors.gray.200')
          }
        }
      }
    }),
    extend: {
      colors: {
        inherit: 'inherit',

        gray: {
          50: "#f9f9f9",
          100: "#f5f5f5",
          200: "#aaa",
          700: "#36393e",
          750: "#2e3136",
          800: "#1e2125",
          900: "#18171b",
        },

        'blue.500': '#42a7ff'
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
    focusVisible,
    typography
  ],
  variants: {
    borderColor: [...variants.borderColor, 'focus-visible'],
    borderStyle: [...variants.borderStyle, 'hover', 'focus'],
    borderWidth: [...variants.borderWidth, 'hover', 'focus'],
    margin: [...variants.margin, 'first', 'last', 'odd', 'even'],
    typography: ['responsive', 'dark']
  },
  experimental: {
    applyComplexClasses: true,
    darkModeVariant: true
  },
  future: {
    purgeLayersByDefault: true
  }
}
