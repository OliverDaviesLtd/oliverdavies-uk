const defaultConfig = require('tailwindcss/defaultConfig')
const defaultTheme = require('tailwindcss/defaultTheme')
const focusVisible = require('./tailwind-plugin-focus-visible')
const typography = require('@tailwindcss/typography')
const { fontFamily } = defaultTheme
const { variants } = defaultConfig

module.exports = {
  important: true,
  purge: {
    mode: 'layers',
    content: [
      '../../../../config/**/*.yml',
      'body-field-values.txt',
      'templates/**/*.twig'
    ],
    safelist: ['bg-gray-200']
  },
  theme: {
    extend: {
      typography: (theme) => ({
        DEFAULT: {
          css: {
            a: {
              color: theme('colors.blue.500')
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
      colors: {
        inherit: 'inherit',

        gray: {
          50: "#f9f9f9",
          100: "#f5f5f5",
          150: '#eee',
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
    typography: ['responsive']
  }
}
