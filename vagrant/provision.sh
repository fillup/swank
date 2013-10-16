#!/usr/bin/env bash

# Install Apache and PHP (and any needed extensions).
sudo apt-get install -y git php5

# Mount synced folder as apache
sudo mount -t vboxsf -o uid=`id -u www-data`,gid=`id -g www-data` /var/www/swank /var/www/swank

# Make sure the timezone is set in php.ini.
sudo sed -i".bak" "s/^\;date\.timezone.*$/date\.timezone = \"America\\/New_York\" /g" /etc/php.ini

# Change apache servername
sudo sed -i "s/#ServerName www.example.com:80/ServerName swank.local:80/g" /etc/httpd/conf/httpd.conf

# Add hosts entries (preventing duplicates by first removing them first if
# they're already there).
#sudo sed -i "s/127.0.0.1 swank.local//g" /etc/hosts
#sudo echo "127.0.0.1 swank.local" >> /etc/hosts

# Retrieve the composer dependencies.
cd /var/www/swank/application
sudo php composer.phar self-update
sudo php composer.phar update --dev

# Give Apache write permission to the folders it needs it for.
sudo chown www-data:www-data /var/www/swank/application/protected/runtime
sudo chown www-data:www-data /var/www/swank/application/assets

# Copy the conf file to where Apache will find it.
sudo cp /vagrant/swank-vhost.conf /etc/httpd/conf.d/

# Adjust the mode settings on that conf file.
sudo chmod 644 /etc/httpd/conf.d/swank-vhost.conf

# Restart Apache.
sudo apachectl restart