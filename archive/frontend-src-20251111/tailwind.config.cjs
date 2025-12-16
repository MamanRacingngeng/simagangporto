module.exports = {
  content: [
    './index.html',
    './src/**/*.{js,jsx,ts,tsx}'
  ],
  theme: {
    extend: {
      colors: {
        primary: '#3B82F6',
        dark: '#1F2937',
        soft: '#F9FAFB'
      },
      boxShadow: {
        'soft-lg': '0 10px 30px rgba(16,24,40,0.08)',
        'neum': '8px 8px 20px rgba(16,24,40,0.06), -8px -8px 20px rgba(255,255,255,0.9)'
      }
    }
  },
  plugins: []
}
