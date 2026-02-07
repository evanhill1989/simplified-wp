# WordPress Engineering

## Child Theme Override Strategy

### Principle
Override only what's necessary. Every override is a maintenance point when TT25 updates.

### Design Authority (Conceptual Ownership)

Each concern has one owner. When deciding *where* a change belongs, consult this table first:

| Concern | Authority | Notes |
|---|---|---|
| Design tokens (colors, type scale, spacing scale) | `theme.json` | Defines the vocabulary of available values |
| Layout & responsive behavior | Custom CSS | Grid, flex, media queries, visual application of tokens |
| Markup structure | Templates (block or PHP) | What HTML exists on the page |
| Dynamic logic & functionality | PHP | Hooks, filters, queries, enqueues |

This is about *ownership*, not cascade priority. `theme.json` does not "outrank" CSS — they govern different concerns.

### Technical Override Cascade

When overriding something inherited from the parent theme, prefer lower-maintenance methods first:

1. **theme.json** — Override parent tokens and settings. Zero specificity issues, lowest maintenance cost.
2. **CSS** — Override visual presentation via stylesheets. Medium maintenance cost.
3. **Block templates** — Copy parent template files into `/templates/` or `/parts/`. Higher maintenance cost; do this only when CSS cannot achieve the goal because the HTML structure itself must change.
4. **PHP** — Hooks, filters, dequeues. Use for behavior changes, not presentation.

### When to Copy a Parent Template

Copy a TT25 block template into the child theme only when:
- The HTML structure itself needs to change (not just styling).
- A block needs to be added or removed from the template.
- WordPress provides no filter or hook to achieve the change.

When copying, add a comment at the top noting which parent version it was copied from, so updates can be tracked.

## Template Strategy

### Block Templates vs PHP Templates

Use **block templates** (`.html` files in `/templates/`) as the primary template system. This is TT25's architecture and fighting it creates friction.

Use PHP templates only when:
- Complex dynamic logic is required that block bindings cannot handle.
- A custom query or data transformation is needed before rendering.
- A third-party integration requires PHP output.

### Template Parts

Use `/parts/` for reusable sections (header, footer, sidebar). Override parent parts by placing a file with the same name in the child theme's `/parts/` directory.

## PHP Standards

### functions.php

- Use a single `functions.php` that enqueues styles and registers any necessary hooks.
- For larger functionality, organize into `/inc/` files and `require` them from `functions.php`.
- Prefix all custom function names with `swp_` (simplified-wp) to avoid conflicts.

### Security

- **Output escaping**: Every echo/output uses `esc_html()`, `esc_attr()`, `esc_url()`, or `wp_kses()` as appropriate. No exceptions.
- **Input sanitization**: All `$_GET`, `$_POST`, `$_REQUEST` values are sanitized with `sanitize_text_field()`, `absint()`, or appropriate sanitizer before use.
- **Nonces**: All form submissions and AJAX requests use `wp_nonce_field()` / `wp_verify_nonce()`.
- **No direct file access**: Every PHP file starts with `defined('ABSPATH') || exit;`.
- **No raw SQL**: Use `$wpdb->prepare()` for any database queries. Prefer WP_Query and WordPress APIs over direct queries.

### Enqueueing

- Enqueue child theme stylesheet with the parent stylesheet as a dependency.
- Use `wp_enqueue_style()` and `wp_enqueue_script()` exclusively. Never hardcode `<link>` or `<script>` tags.
- Use versioning tied to file modification time during development: `filemtime(get_stylesheet_directory() . '/assets/css/main.css')`.

## JavaScript Philosophy

Minimal. No JavaScript unless a specific interaction requires it.

When JS is needed:
- Vanilla JS only. No jQuery (TT25 doesn't load it by default).
- Small, scoped files. No bundler unless complexity warrants it.
- Enqueue via `wp_enqueue_script()` with `defer` or loaded in footer.
- No inline scripts unless required for critical above-the-fold behavior.

## File Structure (Theme)

```
simplified-wp/
  style.css              — Theme header (metadata only, no styles)
  functions.php          — Enqueues, hooks, includes
  theme.json             — Token definitions, editor settings
  templates/             — Block template overrides (only as needed)
  parts/                 — Template part overrides (only as needed)
  assets/
    css/                 — Custom CSS system (see css-architecture.md)
    js/                  — Scripts (if needed)
    images/              — Theme images/icons
  inc/                   — PHP includes (if functions.php grows)
  docs/                  — Architecture documentation (this directory)
```
