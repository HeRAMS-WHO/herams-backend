module.exports = {
  content: [
    "./index.html",
    "src/**/*.{svelte,ts,js}"
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms')

  ],
}
