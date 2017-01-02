#!/usr/bin/env bash
if [ -f ./.vendor/bin/drush ] ; then mv ./.vendor ./vendor ; fi
DRUSH="./vendor/bin/drush"
SITE_ALIAS="@kporras07.kporras07.dev"
$DRUSH $SITE_ALIAS cc drush
echo "Installing..."
if [ -f ./files/config/sync/core.extension.yml ] ; then $DRUSH $SITE_ALIAS si standard --config-dir=sites/default/files/config/sync --account-pass=admin -y ; else $DRUSH $SITE_ALIAS si standard --account-pass=admin -y ; fi
echo "Importing config..."
if [ -f ./files/config/sync/core.extension.yml ] ; then $DRUSH $SITE_ALIAS cim -y ; fi
echo "Cleaning cache..."
$DRUSH $SITE_ALIAS cr
mv ./vendor ./.vendor
