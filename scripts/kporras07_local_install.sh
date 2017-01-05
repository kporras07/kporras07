#!/usr/bin/env bash
if [ -f ./.vendor/bin/drush ] ; then mv ./.vendor ./vendor ; fi
DRUSH="./vendor/bin/drush"
SITE_ALIAS="@kporras07.kporras07.dev"
SITE_UUID="16219eb2-a290-46ad-a8ab-fb8e34672ae7"
$DRUSH $SITE_ALIAS cc drush
echo "Installing..."
if [ -f ./files/config/sync/core.extension.yml ] ; then $DRUSH $SITE_ALIAS si kporras07 --account-pass=admin -y ; else $DRUSH $SITE_ALIAS si kporras07 --account-pass=admin -y ; fi
echo "Set site uuid..."
$DRUSH $SITE_ALIAS config-set "system.site" uuid "$SITE_UUID" -y
echo "Importing config..."
if [ -f ./files/config/sync/core.extension.yml ] ; then $DRUSH $SITE_ALIAS cim -y ; fi
echo "Cleaning cache..."
$DRUSH $SITE_ALIAS cr
mv ./vendor ./.vendor
