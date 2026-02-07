# Workflow Bridge: Decision Rules

This document defines when to use block editor controls, custom CSS, or PHP overrides.

## Decision Matrix

| I need to... | Use |
|---|---|
| Define a color, font size, or spacing value | `theme.json` preset |
| Control which colors/sizes editors can pick | `theme.json` settings |
| Style a block's appearance (color, borders, shadows, transitions) | Custom CSS |
| Control layout (grid columns, flex behavior, positioning) | Custom CSS |
| Make something respond to viewport size | Custom CSS media query |
| Change HTML structure of a page template | Block template override (`/templates/`) |
| Change HTML structure of header/footer | Template part override (`/parts/`) |
| Add a reusable page section for editors | Block pattern (registered via PHP) |
| Enqueue stylesheets or scripts | PHP (`functions.php`) |
| Add WordPress functionality (custom post types, hooks) | PHP (`functions.php` or `/inc/`) |
| Remove parent theme behavior | PHP (unhook parent actions/filters) |
| Add dynamic content that blocks can't handle | PHP template or block binding |

## Overriding Parent Theme Styles

### Preferred Methods (in order)

1. **Neutralize via theme.json**: If TT25 applies a style through its `theme.json`, override the value in the child's `theme.json`. Zero specificity issues.

2. **Override via CSS cascade**: The child theme stylesheet loads after the parent. Use equivalent or slightly higher specificity selectors. Strategies:
   - Match the parent's selector exactly (child CSS loads later, wins by cascade order).
   - If the parent selector is too specific, prefix with `body` or the theme's body class.

3. **Remove via PHP**: If TT25 enqueues a specific stylesheet or registers a style you don't want, dequeue it in `functions.php`:
   ```
   wp_dequeue_style('handle-name');
   ```

4. **Reset in CSS**: For broad parent style neutralization, use a targeted reset in `assets/css/base/_reset.css` that undoes specific TT25 defaults.

### Avoid

- Blanket `!important` rules. Each use should be documented with a comment explaining why it's necessary.
- Disabling the parent stylesheet entirely. TT25's structural styles (block layout, alignments) provide useful defaults.
- Inline styles via PHP `wp_add_inline_style()` for anything that should be in a stylesheet.

## When to Use Block Editor Controls vs CSS Classes

**Use editor controls when:**
- The choice is content-specific (this particular heading should be blue, not all headings).
- The option maps to a theme.json token (color picker, font size selector).
- The editor is the only person who knows the correct value.

**Use CSS classes when:**
- The style applies to all instances of a pattern (all cards have the same padding).
- The style is responsive (changes across breakpoints).
- The style involves layout (grid, flex, positioning).
- The style involves interaction (hover, focus, transitions).

## Adding New Components

When a new visual component is needed:

1. Define its BEM class structure in a new file under `assets/css/components/`.
2. If it needs editor controls, register a block pattern that uses the correct markup and classes.
3. If it needs dynamic data, evaluate whether a block binding or PHP template is needed.
4. Add responsive rules within the component's CSS file.
5. Import the new file in `main.css`.
