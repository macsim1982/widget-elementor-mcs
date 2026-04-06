
import { defineConfig } from 'vite'
import fs from 'fs'
import path from 'path'

function getEntries() {
  const dir = path.resolve(__dirname, 'src/js')
  const files = fs.readdirSync(dir)

  const entries = {}

  files.forEach(file => {
    if (file.endsWith('.js')) {
      const name = file.replace('.js', '')
      entries[name] = path.resolve(dir, file)
    }
  })

  return entries
}

export default defineConfig({
  build: {
    outDir: 'assets',
    emptyOutDir: true,

    rollupOptions: {
      input: getEntries(),
      output: {
        entryFileNames: 'js/[name].js',
        assetFileNames: 'css/[name].css'
      }
    }
  }
})