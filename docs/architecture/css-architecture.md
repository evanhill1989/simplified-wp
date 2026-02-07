# CSS Architecture

## Methodology: Hybrid (BEM + Utilities)

### BEM — For Components

Use BEM naming for any custom component or block override that has internal structure.

```
.block__element--modifier
```

Rules:
- BEM selectors target custom components and WordPress block overrides that need structural styling.
- One block per logical component. Elements describe children. Modifiers describe variants.
- Never nest BEM selectors more than one level deep in the compiled output.
- Prefix custom block overrides with `c-` to distinguish from WordPress's `.wp-block-*` namespace.

Examples:
- `.c-hero__title` — custom hero component's title element
- `.c-card--featured` — featured variant of a card component
- `.wp-block-heading` (no prefix) — direct override of a core WordPress block

### Utilities — For Layout, Spacing, Responsive Helpers

Utility classes handle single-purpose, reusable properties.

Rules:
- Prefix all utilities with `u-` to clearly separate them from component styles.
- Utilities cover: spacing, display, flex/grid shortcuts, visibility, text alignment.
- Utilities should map to theme.json spacing tokens where applicable (e.g., `u-mt-40` maps to `--wp--preset--spacing--40`).
- Keep the utility set small and intentional. Do not replicate Tailwind. Add utilities only when a pattern repeats across 3+ locations.

### Class-First Styling Rule

Selectors must target project-defined classes (`c-`, `u-`, `l-`) rather than raw block or element selectors whenever possible. Do not write selectors like `.wp-block-group h2` — that couples your styles to WordPress's internal DOM structure, which can change across updates. Instead, add a project class to the markup and target that.

WordPress block class overrides (`.wp-block-heading`, `.wp-block-button`) are acceptable only in `assets/css/base/_reset.css` as a normalization layer. Component and layout styles must target project-owned classes.

### Selector Specificity Strategy

1. CSS custom properties (from theme.json) provide the token layer.
2. Utility classes provide single-purpose overrides at low specificity.
3. BEM component selectors provide structural styling at medium specificity.
4. WordPress block overrides may require higher specificity to beat parent theme selectors. Use the child theme's `body` class or a wrapper selector rather than `!important`.

Avoid `!important` except as a last resort against WordPress inline styles that cannot be removed via PHP.

## File Organization

```
assets/
  css/
    base/
      _reset.css          — Normalize/reset parent theme defaults
      _tokens.css         — CSS custom properties (mirrors theme.json tokens for CSS-only contexts)
      _typography.css     — Base type styles
    components/
      _hero.css           — BEM component styles
      _card.css
      _navigation.css
      (one file per component)
    utilities/
      _spacing.css        — u-mt-*, u-mb-*, u-p-* etc.
      _layout.css         — u-flex, u-grid, u-container etc.
      _visibility.css     — u-hidden-*, u-visible-* (responsive)
      _text.css           — u-text-center, u-text-right etc.
    main.css              — Import order: base → components → utilities
```

Import order matters: utilities load last so they can override component defaults when applied.

## Naming Conventions Summary

| Type | Prefix | Example |
|---|---|---|
| Custom component | `c-` | `.c-hero__title` |
| Utility | `u-` | `.u-mt-40` |
| Layout context | `l-` | `.l-grid`, `.l-container` |
| State | `is-` / `has-` | `.is-active`, `.has-overlay` |
| WordPress override | (none) | `.wp-block-heading` |
