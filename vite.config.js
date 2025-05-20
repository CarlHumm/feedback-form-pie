import { defineConfig, loadEnv } from 'vite'
import path from 'path'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '');
  const isDev = mode === 'development';
  const isWordPress = env.VITE_ENV === 'wordpress';
  const isStandard = env.VITE_ENV === 'standard';

  if (!isWordPress && !isStandard) {
    throw new Error('Unknown VITE_ENV. Use "wordpress" or "standard".');
  }

  if (isWordPress && isDev) {
    throw new Error('Whoops... Dev server is temporarily not supported for WordPress. Only build is allowed atm.');
  }

  let base = '/';
  let outDir = 'dist';

  if (isWordPress) {
    base = '/wp-content/plugins/feedback-plugin/';
    outDir = 'feedback-plugin';
  } else if (isStandard) {
    base = './';
    outDir = 'public';
  }

  return {
    base,
    publicDir: false,
    define: {
      'import.meta.env.VITE_ENV': JSON.stringify(env.VITE_ENV),
    },
    build: {
      outDir,
      emptyOutDir: false,
      rollupOptions: {
        input: {
          main: path.resolve(__dirname, 'index.html'),
        },
        output: {
          assetFileNames: 'assets/[name][extname]',
          entryFileNames: 'assets/[name].js',
        },
      },
    },
        resolve: {
        alias: {
          '@': path.resolve(__dirname, 'src'),
        },
      },
  };
});