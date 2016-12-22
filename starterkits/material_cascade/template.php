<?php

/**
 * Implements hook_preprocess_html().
 */
function material_cascade_preprocess_html(&$variables) {
  // Add classes for apply colors.
  $variables['classes_array'][] = 'accent-orange';
  //$variables['classes_array'][] = 'primary-blue-gray';
  //$variables['classes_array'][] = 'navbar-fixed';
}
