#!/usr/bin/env bash

# Install Apache and PHP (and any needed extensions).
sudo apt-get install -y git php5

# Mount synced folder as apache
sudo mount -t vboxsf -o uid=`id -u www-data`,gid=`id -g www-data` /var/www/swank /var/www/swank

# Make sure the timezone is set in php.ini.
sudo sed -i".bak" "s/^\;date\.timezone.*$/date\.timezone = \"America\\/New_York\" /g" /etc/php5/apache2/php.ini

# Copy the conf file to where Apache will find it.
sudo cp /vagrant/swank-vhost.conf /etc/apache2/sites-available/
sudo a2ensite swank-vhost.conf

# Restart Apache.
sudo service apache2 restart