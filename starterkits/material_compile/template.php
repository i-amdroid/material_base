<?php

/**
 * Implements hook_preprocess_html().
 */
function material_compile_preprocess_html(&$variables) {
  // Add classes to body.
  $variables['classes_array'][] = 'navbar-fixed';
}
