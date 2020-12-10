const colors = require('tailwindcss/colors')

module.exports = {
  purge: [],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#fafcf5',
          100: '#f4f8ec',
          200: '#e5eecf',
          300: '#d5e3b1',
          400: '#b5cf77',
          500: '#95ba3d',
          600: '#86a737',
          700: '#708c2e',
          800: '#597025',
          900: '#495b1e',
        },
      },
      fontSize: {
        '2xs': '0.6rem',
      },
      spacing: {
        sm: '2px',
        7: '1.75rem',
      },
      inset: {
        16: '16px',
        '-16': '-16px',
        64: '64px',
      },
      rotate: {
        '-1': '-1deg',
        '-2': '-2deg',
        '-3': '-3deg',
        1: '1deg',
        2: '2deg',
        3: '3deg',
      },
      animation: {
        wiggle: 'wiggle 1s infinite',
      },
      keyframes: {
        wiggle: {
          '0%, 100%': {
            transform: 'scale(1.2) rotate(7deg)',
          },
          '50%': {
            transform: 'scale(0.8) rotate(-7deg)',
          },
        },
      },
      typography: {
        DEFAULT: {
          css: {
            color: '#333',
            a: {
              color: '#3182ce',
              '&:hover': {
                color: '#2c5282',
              },
            },
          },
        },
      },
    },
  },
  variants: {
    backgroundColor: ['responsive', 'odd', 'even', 'hover', 'focus', 'group-hover'],
    borderColor: ['responsive', 'hover', 'focus'],
    borderWidth: ['responsive', 'first', 'last', 'hover', 'focus'],
    opacity: ['responsive', 'hover', 'focus', 'disabled'],
    outline: ['focus'],
    animation: ({after}) => after(['motion-safe', 'motion-reduce']),
    transitionProperty: ({after}) => after(['motion-reduce']),
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
}
