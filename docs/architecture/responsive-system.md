# Responsive System

## Approach: Mobile-First

Base styles target the smallest viewport. Media queries add complexity as the viewport grows.

## Breakpoints

| Token | Value | Target |
|---|---|---|
| `--bp-tablet` | 768px | Tablet portrait and up |
| `--bp-desktop` | 1024px | Desktop and up |
| `--bp-wide` | 1440px | Wide desktop and up |

### Media Query Pattern

Always use `min-width` (mobile-first):

```css
/* Base: mobile */
.c-hero__title { font-size: var(--wp--preset--font-size--large); }

/* Tablet and up */
@media (min-width: 768px) {
  .c-hero__title { font-size: var(--wp--preset--font-size--x-large); }
}

/* Desktop and up */
@media (min-width: 1024px) {
  .c-hero__title { font-size: var(--wp--preset--font-size--xx-large); }
}
```

### Rules

1. Never use `max-width` media queries unless fixing a narrow-only edge case. Document the reason in a comment.
2. Breakpoint values are used directly in media queries (CSS custom properties cannot be used in media queries). Keep them consistent with the values above.
3. Each component's responsive rules live within that component's CSS file, not in a separate responsive file.
4. Utility visibility classes follow the pattern: `u-hidden-until-tablet`, `u-hidden-from-desktop`, etc.

## Container Strategy

### Horizontal Constraints

`.l-container` is the project's primary horizontal constraint class. It sets `max-width` and horizontal centering. Use it on wrapper elements to constrain content width.

`theme.json` defines `contentSize` and `wideSize`, which WordPress uses for its built-in `is-layout-constrained` behavior on block templates. Our `.l-container` should align with `contentSize` so the two systems don't fight.

### Full-Width Sections

Full-width (edge-to-edge) sections are allowed. Use WordPress's `alignfull` class for blocks within constrained layout contexts — TT25's layout system already handles the breakout. For custom sections outside the block flow, use `.l-full-bleed` or equivalent with `width: 100vw` positioning.

Do not recreate WordPress's alignment breakout logic in custom CSS. Use the existing `alignwide` and `alignfull` classes where they apply.

### Gutenberg Alignment Mapping

| Gutenberg alignment | What it does | Our system equivalent |
|---|---|---|
| Default (none) | Constrained to `contentSize` | `.l-container` width |
| `alignwide` | Expands to `wideSize` | No custom equivalent needed; use as-is |
| `alignfull` | Breaks out to viewport edge | No custom equivalent needed; use as-is |

The key rule: inside block templates, use WordPress's alignment system. In custom components outside block flow, use `.l-container` and `.l-full-bleed` project classes. Do not mix the two approaches on the same element.

## Responsive Spacing

Spacing tokens from `theme.json` define the scale. CSS controls responsive application:

- Base (mobile) spacing uses smaller token values.
- Tablet/desktop breakpoints can increase spacing by stepping up the token scale.
- Do not create separate "mobile spacing" and "desktop spacing" token sets. Use the single scale and apply different steps at different breakpoints.
