---
outline: deep
---

# Migrating from Theme Core

This guide will help you migrate from Theme Core to the Creode WordPress Theme Framework.

## Step 1: Ensure you have all composer packages updated
Run the following command to ensure you have all the latest packages installed.

```bash
composer update
```

## Step 2: Install the Creode WordPress Theme Framework

Follow the [installation guide](/installation) to install the Creode WordPress Theme Framework.

## Step 3: Remove the theme core from your project
Using composer, remove the theme core from your project.

```bash
composer remove creode/theme-core
```

## Step 4: Remove the requirement of the theme-core boot file
Remove the following line from your `functions.php` file.

```php
require_once get_template_directory() . '/core/boot.php'; // [!code --]
```

## Step 5: Replace class of register_vite_script function
The assets class from theme core has been removed and instead is now replaced with a function from the new theme framework.

You will need to replace all occurrences and function calls:
```php
use Creode_Theme\Asset_Enqueue; // [!code ++]

function [[THEME_NAME]]_enqueue_script() {
    Assets::register_vite_script( 'header', 'src/components/header.js', array( 'jquery' ), true );   // [!code --]

    $asset_enqueue = Asset_Enqueue::get_instance(); // [!code ++]
    $asset_enqueue->register_vite_script( 'header', 'src/components/header.js', array( 'jquery' ), true );  // [!code ++]
    
    // ... other required code ...
};
add_action( 'wp_enqueue_scripts', '[[THEME_NAME]]_enqueue_script' );
```

This will now ensure that all JS is loaded from Vite correctly.

## Step 6: Remove hot reloading from your vite config file.
Hot reloading was a feature that was a little buggy on the theme core. It is therefore no longer supported in the new theme framework. There may be plans in future to reintroduce this feature.

In order to remove hot reloading, you will need to remove the following line from your vite config file.

```js
import { manageHotReloadFile } from './core/js/hot-reload.js'; // [!code --]

export default defineConfig(
	(command) => {
        manageHotReloadFile(command.mode, DDEV_HOSTNAME, HOT_RELOAD_PORT); // [!code --]

        // ... other vite config code ...
	}
);
```

## Step 7: Move your SCSS folder up a level
In the previous version of the theme core framework, the SCSS folder was located within the `src` folder. As part of this migration, you will need to move the SCSS folder up a level to the root of the project. The `SCSS` folder should now be located in the root of the theme.

For example if you had a `src/scss` folder in the theme, it should now be moved to the root of the theme to `scss/`.

## Step 8: Migrate the component specific CSS files up to the component directory
As part of the changes to how blocks are handled, the SCSS files for each block should now be moved to their relevant block folder.

For example a header.scss component file will now be located in the following folder based on the theme root: `/blocks/header-block/assets/header.scss`.

Each of these components should be moved to their relevant block folder.

This is a change that will need to be done manually and can be quite tedious however it will ensure that all themes and blocks are kept consistent in the future.

## Step 9: Remove individual components imports from the `admin.scss` and `main.scss` files
As part of the changes to how blocks are handled, the individual components `@use` and `@include` from the `admin.scss` and `main.scss` files are no longer required. These are now handled by the theme framework and will be automatically added to the theme in a new `blocks/_all.scss` file.

This is a change that will need to be done manually and can be quite tedious however it will ensure that all themes and blocks are kept consistent in the future.

You can recompile the assets to check over your changes periodically during this process by running the following WP CLI command:
```bash
wp creode-theme:build
```

## Step 10: Clean up scss import paths
After moving the SCSS files to their relevant block folder, you will need to clean up the `@use` paths for each of the SCSS files. Paths to scss files in vite can be absolute based on where the `vite.config.js` file is located. In this case it will be in the theme.

An example of this is demonstrated below with pulling the global file into the `header.scss` file:

```scss
@use "../globals"; // [!code --]
@use "/scss/globals"; // [!code ++]
```

This change can be quite tedious to do manually however we want to ensure that all themes keep the same structure and paths so a change like this will help our projects stay consistent in the future.

## Step 11: Run the script to install any framework files and compile assets
As part of the theme framework, there is a WordPress CLI command that will install any missing files and compile the assets. This ensures that the structure of the theme is kept consistent and that new files as part of the boilerplate can be added automatically to themes without having to keep track of them manually.

## Step 12: Remove the admin and main js files from src
As part of the framework, the main.js and admin.js within the `src` folder are no longer required. These are now handled by the theme framework and will be automatically added to the theme in a new `vite-entry-points` folder. These files are no longer required and can be removed from the `src` folder, if there is more content to them, ensure this is now merged with the newly created equivalent files in the `vite-entry-points` folder.

## Step 13: Update the vite config file to switch these entrypoints over
The `vite.config.js` file will need to be updated to switch these entrypoints over to the new `vite-entry-points` folder.

```js
export default defineConfig(
	(command) => {
		return {
            // ... other vite config code ...
			build: {
				rollupOptions: {
					// overwrite default .html entry
					input: {
                        main: resolve(__dirname, 'src/main.js'), // [!code --]
						admin: resolve(__dirname, 'src/admin.js'), // [!code --]
                        main: resolve(__dirname, 'vite-entry-points/main.js'), // [!code ++]
						admin: resolve(__dirname, 'vite-entry-points/admin.js'), // [!code ++]
                        // ... other vite config code ...
					}
				}
			}
		}
	}
);
```


## Step 14: Import the new `blocks/_all.scss` file into the main.scss file
Add the following to the top of the `main.scss` file:

```scss
// Use base elements
@use 'base';

// Use blocks  // [!code ++]
@use '/blocks/all' as blocks; // [!code ++]

// ...other scss code...

// Render base elements
@include base.render;

// Render blocks  // [!code ++]
@include blocks.render; // [!code ++]
```

This will ensure that all blocks are loaded into the theme.

## Step 15: Import the new `blocks/_all.scss` file into the admin.scss file
This process is very similar to the `main.scss` file however you will need to ensure that any admin specific mixins are handled manually using the new folder location. The new blocks all file only handle the render mixin and admin ones will need to be handled manually. See the below example for how this has changed:

```scss
@use 'components/header'; // [!code --]
@use '/blocks/header-block/assets/_header' as header; // [!code ++]

// ...other scss code...

@include header.render; // [!code --]
@include header.admin; // [!code ++]
```

Add the following to the `admin.scss` file:

```scss
// Use base elements
@use 'base';

// Use blocks
@use '/blocks/all' as blocks; // [!code ++]

// ...other scss code...

// Render base elements
@include base.render;

// Render blocks // [!code ++]
@include blocks.render; // [!code ++]
```

## Step 16: Recompile the assets
Once these steps have been completed, you will need to recompile the assets. This can be done by running the following WP CLI command:

```bash
wp creode-theme:build
```
