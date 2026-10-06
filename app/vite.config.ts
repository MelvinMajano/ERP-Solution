import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'
import path from 'path';
import tailwindcss from '@tailwindcss/vite'


// https://vite.dev/config/
export default defineConfig({
  plugins: [react(),tailwindcss(),],
  resolve: {
    alias: {
      '@config': path.resolve(__dirname, './src/config'),
      '@infrastructure': path.resolve(__dirname, './src/infrastructure'),
      '@domain': path.resolve(__dirname, './src/domain'),
      '@modules': path.resolve(__dirname, './src/modules'),
    },
  },
})
