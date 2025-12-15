import defaultTheme from 'tailwindcss/defaultTheme'

export default {
  content: ['./resources/**/*.{js,vue,blade.php}'],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        kids: {
          light: '#FFEB3B',
          dark: '#FBC02D'
        },
        youth: {
          light: '#03A9F4',
          dark: '#0288D1'
        },
        adult: {
          light: '#8BC34A',
          dark: '#558B2F'
        }
      },
      fontSize: {
        small: '0.875rem',
        medium: '1rem',
        large: '1.25rem'
      }
    },
    fontFamily: {
      sans: ['"Cerebri Sans"', ...defaultTheme.fontFamily.sans],
    },
  },
}
