<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in html.tpl.php and page.tpl.php.
 * Some may be blank but they are provided for consistency.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 *
 * @ingroup themeable
 */
?><!DOCTYPE html>
<html>
  <head>
    <?php print $head; ?>
    <title><?php print $head_title; ?></title>
    <?php print $styles; ?>
    <?php print $scripts; ?>
  </head>
  <body class="<?php print $classes; ?>">
    <div id="skip-link">
      <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
    </div>
    <?php print $page_top; ?>
    <div id="page">
      <?php if ($title): ?>
        <div class="maintenance-info">
          <?php print $title; ?>
        </div>
      <?php endif; ?>
      <?php if ($logo || $site_name): ?>
        <div id="logo-box">
          <?php if ($logo): ?>
            <img src="<?php print $logo; ?>"/>
          <?php elseif ($site_name): ?>
            <h1><?php print $site_name; ?></h1>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="maintenance-info">
        <?php print $content; ?>
      </div>
    </div>
    <?php print $page_bottom; ?>
  </body>
</html>
