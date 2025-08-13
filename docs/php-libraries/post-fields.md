---
outline: deep
---

# Post Fields

## Base class extension
The abstract class Creode_Theme\Base_Post_Fields can be extended within your theme to declare a field group against a single post type or multiple post types.

The abstract class uses a singleton design pattern therefore cannot be publicly instantiated. each extension must be initialized by calling the it's static init function.

The base class uses ACF Pro to manage these fields therefore this must be installed.

Extension classes must define information about the field group and an ACF fields array.

The base class works by assigning the field group to post types which support a particular custom feature (support). This can be defined by extending the support function and returning the applicable support feature string.

```php
/**
 * Class to register post logo fields.
 */
class Logo_Post_Fields extends Base_Post_Fields {

	/**
	 * {@inheritdoc}
	 */
	protected function group_name(): string {
		return 'logo';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function group_title(): string {
		return 'Logo';
	}

	/**
	 * {@inheritdoc}
	 */
	protected function fields(): array {
		return array(
			array(
				'key'           => 'field_logo',
				'name'          => 'logo',
				'label'         => 'Logo',
				'type'          => 'image',
				'return_format' => 'ID',
			),
		);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function support(): string {
		return 'logo';
	}
}
```

## Custom supports

The example above requires that applicable post types support the "logo" custom feature (support). This means that custom post types must include this within their supports array:

```php
/**
 * {@inheritdoc}
 */
protected function args(): array {
  return array(
    'supports' => array(
      'title',
      'editor',
      'thumbnail',
      'excerpt',
      'logo',
    ),
    'show_in_rest' => true,
    'has_archive'  => true,
  );
}
```

Pre-existing post types can have additional supports added by using the add_post_type_support function: https://developer.wordpress.org/reference/functions/add_post_type_support/

## File architecture
During a theme installation, a folder will be created at the following path, relative to the theme root: /includes/post-fields. Extension class files should be created within this folder. The folder also contains a file called all.php. This file should used to require extension class files and initialize them.

```php
require_once __DIR__ . 'class-logo-post-fields.php';
Logo_Post_Fields::init();
```