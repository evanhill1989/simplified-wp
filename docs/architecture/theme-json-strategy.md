# theme.json Strategy

## Role

`theme.json` is the **token definition layer**. It declares the design vocabulary (which colors, sizes, and spacing values exist). It does **not** control how those tokens are applied across breakpoints or layouts — that's CSS's job.

## Ownership Map

| Concern | Owner | Notes |
|---|---|---|
| Color palette | theme.json | All colors defined as presets. Editor exposes the curated palette. |
| Typography scale | theme.json | Font sizes defined as presets. Editor exposes the curated scale. |
| Font families | theme.json | Registered and assigned here. Unregister unwanted parent fonts. |
| Spacing scale | theme.json | Token values defined here. CSS controls responsive application. |
| Layout (contentSize, wideSize) | theme.json | Sets max-width constraints. CSS handles grid/flex within them. |
| Layout grid behavior | Custom CSS | Grid columns, gap patterns, responsive reflow. |
| Responsive behavior | Custom CSS | All media queries and viewport-dependent logic. |
| Component styling | Custom CSS | Block appearance, hover states, transitions. |

## Settings Philosophy

### Colors
- Define a curated palette. Disable custom colors to enforce consistency.
- Include semantic names: `primary`, `secondary`, `accent`, `foreground`, `background`, `muted`.
- Disable gradients unless specifically needed.

### Typography
- Define a type scale with semantic names: `small`, `medium`, `large`, `x-large`, `xx-large`.
- Set base font family and heading font family.
- Disable custom font sizes to keep editors within the scale.

### Spacing
- Define a spacing scale using the WordPress `spacingSizes` array.
- Use a consistent progression (e.g., based on a 4px or 8px base unit).
- These become CSS custom properties: `--wp--preset--spacing--{slug}`.
- CSS references these tokens for all margin, padding, and gap values.

### Layout
- Set `contentSize` (readable content width) and `wideSize` (wide content width).
- These control WordPress's built-in `is-layout-constrained` behavior.

### Appearance Tools
- Enable `appearanceTools` to expose border, spacing, and typography controls in the editor, then selectively restrict via `blocks` settings where tighter control is needed.

## Relationship to Parent theme.json

The child theme's `theme.json` merges with TT25's `theme.json`. Strategy:

1. **Override** color palette entirely — replace, don't extend.
2. **Override** typography scale and font families entirely.
3. **Override** spacing scale entirely.
4. **Keep** layout settings from parent unless specific widths need changing.
5. **Restrict** specific block settings in the `blocks` key to enforce guided freedom.

## Generated CSS

WordPress generates CSS custom properties from `theme.json` automatically. Do not manually duplicate these in CSS files. Reference them directly:

```css
color: var(--wp--preset--color--primary);
font-size: var(--wp--preset--font-size--large);
padding: var(--wp--preset--spacing--40);
```

If a token is needed in CSS but doesn't belong in `theme.json` (e.g., a z-index scale, transition durations), define it in `assets/css/base/_tokens.css` as a standard CSS custom property.
