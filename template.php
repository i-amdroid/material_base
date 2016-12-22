<?php

/**
 * Implements hook_preprocess_node().
 *
 * Backports the following changes made to Drupal 8:
 * - #1077602: Convert node.tpl.php to HTML5.
 */
function material_base_preprocess_node(&$variables) {
  // Add article ARIA role.
  $variables['attributes_array']['role'] = 'article';
}

/**
 * Implements hook_preprocess_html().
 *
 * Backports the following changes made to Drupal 8:
 * - #1077566: Convert html.tpl.php to HTML5.
 */
function material_base_preprocess_html(&$variables) {
  // Initializes attributes which are specific to the html and body elements.
  $variables['html_attributes_array'] = array();
  $variables['body_attributes_array'] = array();

  // HTML element attributes.
  $variables['html_attributes_array']['lang'] = $GLOBALS['language']->language;
  $variables['html_attributes_array']['dir'] = $GLOBALS['language']->direction ? 'rtl' : 'ltr';

  // Update RDF Namespacing.
  if (module_exists('rdf')) {
    // Adds RDF namespace prefix bindings in the form of an RDFa 1.1 prefix
    // attribute inside the html element.
    $prefixes = array();
    foreach (rdf_get_namespaces() as $prefix => $uri) {
      $vars['html_attributes_array']['prefix'][] = $prefix . ': ' . $uri . "\n";
    }
  }

  // Add viewport meta tag.
  $viewport = array(
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width, initial-scale=1',
    ),
  );
  drupal_add_html_head($viewport, 'viewport');
}

/**
 * Implements hook_process_html().
 *
 * Backports the following changes made to Drupal 8:
 * - #1077566: Convert html.tpl.php to HTML5.
 */
function material_base_process_html(&$variables) {
  // Flatten out html_attributes and body_attributes.
  $variables['html_attributes'] = drupal_attributes($variables['html_attributes_array']);
  $variables['body_attributes'] = drupal_attributes($variables['body_attributes_array']);
}

/**
 * Implements hook_css_alter().
 */
function material_base_css_alter(&$css) {
  unset($css['modules/system/system.menus.css']);
  unset($css['modules/system/system.theme.css']);
}

/**
 * Implements hook_theme().
 */
function material_base_theme($existing, $type, $theme, $path) {
  return array(
    'search_form_wrapper' => array(
      'render element' => 'element',
    ),
  );
}

/**
 * Implements theme_breadcrumb().
 */
function material_base_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    foreach ( $breadcrumb as $value) {
      $crumbs[] = '<span class="inline">' . $value . '</span>';
    }
    $output .= '<div class="breadcrumb">' . implode(' <span class="delimiter">&gt;</span> ', $crumbs) . '</div>';
    return $output;
  }
}

/**
 * Implements hook_form_alter().
 */
function material_base_form_alter(&$form, &$form_state, $form_id) {
  switch ($form_id) {
    case 'user_login':
    case 'user_login_block':
      $form['#attributes']['class'][] = 'card card-form';
      $form['links'] = Null;
      $form['user_icon']['#markup'] = '<div class="user-icon align-center"><i class="material-icons text-disabled">lock</i></div>';
      $form['user_icon']['#weight'] = -15;
      $form['name']['#title'] = Null;
      $form['name']['#attributes']['placeholder'] = t('Login');
      $form['name']['#description'] = Null;
      $form['pass']['#title'] = Null;
      $form['pass']['#attributes']['placeholder'] = t('Password');
      $form['pass']['#description'] = Null;
      $form['actions']['#attributes']['class'][] = 'card-item card-actions divider-top';
      $form['actions']['submit']['#attributes']['class'][] = 'btn-accent';
      $form['actions']['request_pass']['#markup'] = '<a class="btn pull-right" href="/user/password">' . t('Request new password') . '</a>';
      break;
    case 'user_pass':
      $form['#attributes']['class'][] = 'card card-form';
      $form['user_icon']['#markup'] = '<div class="user-icon align-center"><i class="material-icons text-disabled">vpn_key</i></div>';
      $form['user_icon']['#weight'] = -15;
      $form['name']['#title'] = Null;
      $form['name']['#attributes']['placeholder'] = t('Login or E-mail');
      $form['actions']['#attributes']['class'][] = 'card-item card-actions divider-top';
      $form['actions']['submit']['#attributes']['class'][] = 'btn-accent';
      break;
    case 'user_register_form':
      $form['#attributes']['class'][] = 'card card-form';
      $form['user_icon']['#markup'] = '<div class="user-icon align-center"><i class="material-icons text-disabled">account_circle</i></div>';
      $form['user_icon']['#weight'] = -15;
      $form['account']['name']['#title'] = Null;
      $form['account']['name']['#attributes']['placeholder'] = t('Login');
      $form['account']['mail']['#title'] = Null;
      $form['account']['mail']['#attributes']['placeholder'] = t('E-mail');
      $form['actions']['#attributes']['class'][] = 'card-item card-actions divider-top';
      $form['actions']['submit']['#attributes']['class'][] = 'btn-accent';
      break;
    case 'search_block_form':
      $form['search_block_form']['#attributes']['placeholder'] = t('Search');
      $form['search_block_form']['#theme_wrappers'] = array('search_form_wrapper');
      $form['actions']['#attributes']['class'][] = 'element-invisible';
      break;
  }
}

/**
 * Theme function implementation for search_form_wrapper.
 */
function material_base_search_form_wrapper($variables) {
  $output = '<div class="form-item icon-left" data-icon="search">';
  $output .= $variables['element']['#children'];
  $output .= '</div>';
  return $output;
}

/**
 * Implements hook_form_views_exposed_form_alter().
 */
function material_base_form_views_exposed_form_alter(&$form, &$form_state, $form_id) {
  if (isset($form['search_api_views_fulltext'])) {
    // 'search_api_views_fulltext' should be changed if id was changed in interface
    $form['search_api_views_fulltext']['#attributes']['placeholder'] = t('Search');
    $form['search_api_views_fulltext']['#theme_wrappers'] = array('search_form_wrapper');
    $form['submit']['#attributes']['class'][] = 'element-invisible';
  }
}

/**
 * Implements hook_menu_local_tasks_alter().
 */
function material_base_menu_local_tasks_alter(&$data){
  if(isset($data['tabs'][0]['output'])) {
    foreach ($data['tabs'][0]['output'] as $key => $value) {
      switch ($value['#link']['path']) {
        case 'user/login':
        case 'user/password':
        case 'user/register':
          unset($data['tabs'][0]['output'][$key]);
          break;
      }
    }
  }
}
