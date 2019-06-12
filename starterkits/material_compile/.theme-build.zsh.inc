# Drupal theme watch/build with gulp
#
# get drupal version:
# drush st --format=list drupal-version
#
# Drupal 8, get theme name:
# drush cget system.theme default --format=string
#
# Drupal 8, get theme path:
# drush dd $(drush cget system.theme default --format=string)
#
# Drupal 8, run gulp:
# (cd $(drush dd $(drush cget system.theme default --format=string)) && gulp)
# or:
# gulp --cwd $(drush dd $(drush cget system.theme default --format=string))
#
# Drupal 8, run gulp build:
# (cd $(drush dd $(drush cget system.theme default --format=string)) && gulp build)
# or:
# gulp build --cwd $(drush dd $(drush cget system.theme default --format=string))
#
# Drupal 7, get theme name:
# drush vget theme_default --format=string
#
# Drupal 7, get theme path:
# drush dd $(drush vget theme_default --format=string)
#
# Drupal 7, run gulp:
# (cd $(drush dd $(drush vget theme_default --format=string)) && gulp)
# or:
# gulp --cwd $(drush dd $(drush vget theme_default --format=string))
#
# Drupal 7, run gulp:
# (cd $(drush dd $(drush vget theme_default --format=string)) && gulp build)
# or:
# gulp build --cwd $(drush dd $(drush vget theme_default --format=string))
#
# Zsh function:

function dtw() {
  v=$(drush st --format=list drupal-version)
  if [ ${v%%.*} -eq 8 ]; then
    gulp --cwd $(drush dd $(drush cget system.theme default --format=string))
  elif [ ${v%%.*} -eq 7 ]; then
    gulp --cwd $(drush dd $(drush vget theme_default --format=string))
  else
    echo "Unsupported Drupal version";
  fi
}

function dtb() {
  v=$(drush st --format=list drupal-version)
  if [ ${v%%.*} -eq 8 ]; then
    gulp build --cwd $(drush dd $(drush cget system.theme default --format=string))
  elif [ ${v%%.*} -eq 7 ]; then
    gulp build --cwd $(drush dd $(drush vget theme_default --format=string))
  else
    echo "Unsupported Drupal version";
  fi
}

