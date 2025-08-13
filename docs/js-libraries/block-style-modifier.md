---
outline: deep
---

# Block Style Modifier

## Introduction
This library will allow block styles to be added and removed without the dependency, sequencing and context complications that must be accounted for when using core WordPress functionality.

This will support the style management of both core and custom blocks.

## The class
You will need to instantiate the Block_Style_Modifier class.

```js
const blockStyleModifier = new Block_Style_Modifier();
```

## Removal
To remove a block style you can use the removeStyle function.

```js
blockStyleModifier.removeStyle( 'core/button', 'outline' );
```

## Addition
To add a block style you can use the addStyle function.

```js
blockStyleModifier.addStyle( 'core/button', 'arrow', 'Arrow' );
```

## File architecture
During a theme installation, a file will be created at the following path, relative to the theme root: /js/admin/block-styles.js. Functionality associated with enqueueing this file will also be installed. Implementation of the Block_Style_Modifier class should be added to this file.
