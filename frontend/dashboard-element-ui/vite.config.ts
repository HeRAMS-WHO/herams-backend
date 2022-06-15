import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [svelte({
    compilerOptions: {
      customElement: false,
      generate: "dom",
      format: "esm"
      
    },
  })],
  build: {
    target: "esnext",
    minify: false,
    lib: {
      entry: 'src/App.svelte',
      formats: ['es'],
    },
    rollupOptions: {
      
    //   output: {
    //     entryFileNames: `assets/entry-[name].js`,
    //     chunkFileNames: `assets/chunk-[name].js`,
    //     assetFileNames: `assets/asset-[name][extname]`
    //   }
    }
    
  }
})
