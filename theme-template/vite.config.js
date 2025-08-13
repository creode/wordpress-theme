import { defineConfig } from 'vite';
import { resolve } from 'path';

const DDEV_HOSTNAME = `${process.env.DDEV_HOSTNAME}`;
const HOT_RELOAD_PORT = '5173';

export default defineConfig(
	(command) => {
		return {
			// Increase assetsInlineLimit (in bytes) to allow for larger assets to be inlined
			// If they are larger they get put in /dist with a relative path which doesn't work in the iframe block preview.
			esbuild: {
				minifyIdentifiers: false
			},
			assetsInlineLimit: 12000,
			base: '/wp-content/themes/:THEME_NAME/dist',
			build: {
				// generate .vite/manifest.json in outDir
				manifest: true,
				rollupOptions: {
					// overwrite default .html entry
					input: {
						blockStyles: resolve(__dirname, 'js/admin/block-styles.js'),
						main: resolve(__dirname, 'vite-entry-points/main.js'),
						admin: resolve(__dirname, 'vite-entry-points/admin.js'),
					}
				},
				outDir: 'dist',
				minify: 'esbuild'
			},
			server: {
				// respond to all network requests (same as '0.0.0.0')
				host: true,
				// we need a strict port to match on PHP side
				strictPort: true,
				port: HOT_RELOAD_PORT,
				hmr: {
					// Force the Vite client to connect via SSL
					// This will also force a "https://" URL in the hot file
					protocol: 'wss',
					// The host where the Vite dev server can be accessed
					// This will also force this host to be written to the hot file
					host: DDEV_HOSTNAME,
				}
			},
		}
	}
);
