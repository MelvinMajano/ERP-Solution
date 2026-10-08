import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'
import path from 'path';
import tailwindcss from '@tailwindcss/vite'


// https://vite.dev/config/
export default defineConfig({
  plugins: [react(),tailwindcss(),],
  resolve: {
    alias: {
      '@config': path.resolve(import.meta.dirname, './src/config'),
      '@infrastructure': path.resolve(import.meta.dirname, './src/infrastructure'),
      '@domain': path.resolve(import.meta.dirname, './src/domain'),
      '@modules': path.resolve(import.meta.dirname, './src/modules'),
    },
  },
})
