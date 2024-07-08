import { defineConfig, loadEnv } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig(({ command, mode }) => {
  // Load environment variables based on the mode
  const env = loadEnv(mode, process.cwd());

  return {
    plugins: [react()],
    build: {
      sourcemap: false,
    },
    server: {
      proxy: {
        '/api': {
          target: env.VITE_PROXY, // Environment variable from .env file
          changeOrigin: true,
          secure: false,
          rewrite: (path) => path.replace(/^\/api/, ''),
        },
      },
    },
    define: {
      'process.env': {
        ...process.env,
        ...env, // Merge process.env and loaded env variables
      },
    },
  };
});
