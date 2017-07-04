#!/usr/bin/env sh

set -e

TMP_DIR="$(mktemp -d)"
SEED_FILE='/root/.provisioning_done'
WP_URL="http://localhost:8080"
DOCUMENT_ROOT="/var/www/html"

trap 'rm -rf "$TMP_DIR"' EXIT INT QUIT TERM

if [ -f $SEED_FILE ];
then
    printf "\nVM provisioning gia' completato.\n"
    printf "Puoi accedere all'installazione di WP all'indirizzo: %s\n" "$WP_URL"
    exit 0
fi

# Pre-requisites
export DEBIAN_FRONTEND="noninteractive"
apt-get update
printf "mysql-server mysql-server/root_password password root" | debconf-set-selections
printf "mysql-server mysql-server/root_password_again password root" | debconf-set-selections

# Install LAMP stack
apt-get install -y apache2 php mysql-server libapache2-mod-php php-mysql

# Remove default index.html & reload apache2 (required to load the new installed extensions)
rm $DOCUMENT_ROOT/index.html
service apache2 restart

# Setup database
mysql -u root -proot -e "CREATE DATABASE IF NOT EXISTS wordpress"

# Download latest version of wordpress
wget -O "$TMP_DIR/wordpress.tar.gz" https://wordpress.org/latest.tar.gz
tar xvfz "$TMP_DIR/wordpress.tar.gz" --strip 1 -C $DOCUMENT_ROOT

# Initialize wp-config.php with DB infos
cp $DOCUMENT_ROOT/wp-config-sample.php $DOCUMENT_ROOT/wp-config.php
chown www-data:www-data -R $DOCUMENT_ROOT
sed -i "/DB_HOST/s/'[^']*'/'localhost'/2" $DOCUMENT_ROOT/wp-config.php
sed -i "/DB_NAME/s/'[^']*'/'wordpress'/2" $DOCUMENT_ROOT/wp-config.php
sed -i "/DB_USER/s/'[^']*'/'root'/2" $DOCUMENT_ROOT/wp-config.php
sed -i "/DB_PASSWORD/s/'[^']*'/'root'/2" $DOCUMENT_ROOT/wp-config.php

# Symlink spid-wordpress source code to the WP plugin directory
ln -s /spid-wordpress $DOCUMENT_ROOT/wp-content/plugins

# Provisioning completed
touch $SEED_FILE
printf "\nVM provisioning completato.\n"
printf "Puoi accedere all'installazione di WP all'indirizzo: %s\n" "$WP_URL"
