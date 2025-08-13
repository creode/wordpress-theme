---
outline: deep
---

# Custom Post Types

## Base class extension
The abstract class Creode_Theme\Base_Post_Type can be extended within your theme to declare a custom post type.

The abstract class uses a singleton design pattern therefore cannot be publicly instantiated. each extension must be initialized by calling the it's static init function.

## File architecture
During a theme installation, a folder will be created at the following path, relative to the theme root: /includes/post-types. Extension class files should be created within this folder. The folder also contains a file called all.php. This file should used to require extension class files and initialize them.

```php
require_once __DIR__ . 'class-my-example-post-type.php';
My_Example_Post_Type::init();
```