# Gutenberg Editor Boundaries

## Philosophy: Guided Freedom

The block editor exposes a curated set of options drawn from our design tokens. Editors can make choices within the system but cannot break out of it. CSS has final visual authority.

## What Editors CAN Do

- Choose from the defined **color palette** for text and backgrounds.
- Select from the defined **font size scale** for text blocks.
- Apply **spacing presets** for block padding/margin (from the spacing scale).
- Use standard **alignment options** (left, center, right, wide, full).
- Add, remove, and reorder **blocks** within templates.
- Use all **core blocks** that are not explicitly restricted.

## What Editors CANNOT Do

- Enter **custom color values** (hex/rgb input disabled).
- Enter **custom font sizes** (only preset scale available).
- Use **custom spacing values** (only preset scale available).
- Apply **gradients** (disabled unless a specific need arises).
- Change **font families** per-block (global font family set in theme.json).

## theme.json Settings That Enforce This

```
settings.color.custom: false
settings.color.customGradient: false
settings.color.defaultPalette: false    (hides WordPress default colors)
settings.typography.customFontSize: false
settings.spacing.customSpacingSize: false
settings.typography.fontFamilies: [only our defined families]
```

## Block-Level Restrictions

Some blocks may need tighter or looser controls than the global settings. Use the `settings.blocks` key in `theme.json` to override per block.

Examples of block-level decisions (to be defined during implementation):
- **Buttons**: Restrict to palette colors only, no gradients, no custom border radius.
- **Cover block**: May need gradient support as an exception.
- **Spacer block**: Consider disabling entirely and handling spacing through CSS.

## Editor CSS

WordPress loads `theme.json`-generated styles in the editor automatically. For the editor to accurately preview our custom CSS:

- Enqueue the main CSS stylesheet in the editor via `add_editor_style()` or `wp_enqueue_block_editor_assets`.
- Test that responsive behavior degrades gracefully in the editor's fixed-width viewport (the editor does not simulate responsive breakpoints).

## Block Patterns and Reusable Blocks

- Register custom **block patterns** for common page sections. These encode our HTML structure and class usage.
- Patterns are the primary tool for guiding editors toward correct markup.
- Use patterns to reduce the chance of editors creating ad-hoc layouts that fall outside the CSS system.
