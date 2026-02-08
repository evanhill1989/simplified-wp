<?php
/**
 * Theme functions
 */

add_action('wp_enqueue_scripts', function () {
  // Ensure the theme's main stylesheet loads.
  wp_enqueue_style(
    'simplifiedwp-style',
    get_stylesheet_uri(),
    array(),
    filemtime(get_stylesheet_directory() . '/style.css')
  );
}, 20);