/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./assets/**/*.css",
  ],
  theme: {
    extend: {
      colors: {
        // Definiowanie kolorów marki
        brand: {
          primary: '#4F46E5', // indigo-600
          secondary: '#1D4ED8', // blue-700
          accent: '#0EA5E9', // sky-500
          light: '#EFF6FF', // blue-50
          dark: '#1E293B', // slate-800
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      spacing: {
        // Własne odstępy, jeśli potrzebne
        '128': '32rem',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '2rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'), // Dodanie wsparcia dla typografii
  ],
}
