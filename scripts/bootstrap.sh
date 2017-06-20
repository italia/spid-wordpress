#!/usr/bin/env bash

apt-get update
export DEBIAN_FRONTEND="noninteractive"

# Setup LAMP
debconf-set-selections <<< "mysql-server mysql-server/root_password password root"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password root"
apt-get install -y apache2 php mysql-server libapache2-mod-php php-mysql

# Creo il DB
mysql -u root -proot  -e "CREATE DATABASE IF NOT EXISTS wordpress"

cd /var/www/html
# Scarico ultima versione
curl -O https://wordpress.org/latest.tar.gz

# Estrai archivio
tar xvfz latest.tar.gz



# Imposta permessi
chown -R www-data:www-data wordpress/

# Installo il plugin
cd /tmp
git clone https://github.com/ItalianLinuxSociety/spid-wordpress
cp -R spid-wordpress/spid-wordpress /var/www/html/wordpress/wp-content/plugins
chown -R www-data:www-data /var/www/html/wordpress/wp-content/plugins

# Restart apache2
service apache2 restart

# Fine
echo Fine.