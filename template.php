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
 * Implements template_preprocess_maintenance_page().
 */
function material_base_preprocess_maintenance_page(&$variables) {
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
  unset($css['modules/system/system.messages.css']);
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
      $form['user_icon']['#markup'] = '<div class="user-icon text-align-center"><i class="material-icons text-disabled">lock</i></div>';
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
      $form['user_icon']['#markup'] = '<div class="user-icon text-align-center"><i class="material-icons text-disabled">vpn_key</i></div>';
      $form['user_icon']['#weight'] = -15;
      $form['name']['#title'] = Null;
      $form['name']['#attributes']['placeholder'] = t('Login or E-mail');
      $form['actions']['#attributes']['class'][] = 'card-item card-actions divider-top';
      $form['actions']['submit']['#attributes']['class'][] = 'btn-accent';
      break;
    case 'user_register_form':
      $form['#attributes']['class'][] = 'card card-form';
      $form['user_icon']['#markup'] = '<div class="user-icon text-align-center"><i class="material-icons text-disabled">account_circle</i></div>';
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

/**
 * Implements theme_pager().
 */
function material_base_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-item', 'item-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-item', 'item-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-item', 'item-ellipsis'),
          'data' => '<span><span>&hellip;</span></span>',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-item', 'item-current'),
            'data' => '<span>' . $i . '</span>',
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-item', 'item-ellipsis'),
          'data' => '<span><span>&hellip;</span></span>',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-item', 'item-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-item', 'item-last'),
        'data' => $li_last,
      );
    }
    $output = '<nav class="pager">';
    $output .= '<h2 class="element-invisible">' . t('Pages') . '</h2>';
    $output .= theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager-items')),
    ));
    $output .= '</nav>';
    return $output;
  }
}

/**
 * Implements theme_pager_link().
 */
function material_base_pager_link($variables) {
  $text = $variables['text'];
  $page_new = $variables['page_new'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $attributes = $variables['attributes'];

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('« first') => t('Go to first page'),
        t('‹ previous') => t('Go to previous page'),
        t('next ›') => t('Go to next page'),
        t('last »') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }

  $attributes['href'] = url($_GET['q'], array('query' => $query));
  return '<a' . drupal_attributes($attributes) . '><span>' . check_plain($text) . '</span></a>';
}

/**
 * Implements theme_views_mini_pager().
 */
function material_base_views_mini_pager($vars) {
  global $pager_page_array, $pager_total;

  $tags = $vars['tags'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];

  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  if ($pager_total[$element] > 1) {

    $li_previous = theme('pager_previous',
      array(
        'text' => (isset($tags[1]) ? $tags[1] : t('‹‹')),
        'element' => $element,
        'interval' => 1,
        'parameters' => $parameters,
      )
    );
    if (empty($li_previous)) {
      $li_previous = '<span><span>' . t('‹‹') . '</span></span>';
    }

    $li_next = theme('pager_next',
      array(
        'text' => (isset($tags[3]) ? $tags[3] : t('››')),
        'element' => $element,
        'interval' => 1,
        'parameters' => $parameters,
      )
    );

    if (empty($li_next)) {
      $li_next = '<span><span>' . t('››') . '</span></span>';
    }

    $items[] = array(
      'class' => array('pager-item item-previous'),
      'data' => $li_previous,
    );

    $items[] = array(
      'class' => array('pager-item item-current'),
      'data' => '<span>' . t('@current of @max', array('@current' => $pager_current, '@max' => $pager_max)) . '</span>',
    );

    $items[] = array(
      'class' => array('pager-item item-next'),
      'data' => $li_next,
    );
    $output = '<nav class="pager">';
    $output .= theme('item_list', array(
      'items' => $items,
      'title' => NULL,
      'type' => 'ul',
      'attributes' => array('class' => array('pager-items')),
    ));
    $output .= '</nav>';
    return $output;
  }
}

/**
 * Implements theme_status_messages().
 */
function material_base_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"messages messages-$type\">\n";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= reset($messages);
    }
    $output .= "</div>\n";
  }
  return $output;
}
