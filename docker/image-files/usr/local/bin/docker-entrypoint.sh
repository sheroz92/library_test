#!/bin/bash

# This script is run within the php containers on start

# Fail on any error
set -o errexit

# Set permissions based on ENV variable (debian only)
if [ -x "usermod" ] ; then
    usermod -u ${PHP_USER_ID} www-data
fi

# Enable xdebug by ENV variable
if [ 0 -ne "${PHP_ENABLE_XDEBUG:-0}" ] ; then
    docker-php-ext-enable xdebug
    echo "Enabled xdebug"
fi
echo ${PHP_USER_NAME}
useradd -m -u ${PHP_USER_ID} -o -s /bin/bash ${PHP_USER_NAME} || echo "User already exists."
usermod -a -G www-data ${PHP_USER_NAME}

chown -R ${PHP_USER_NAME} /home/${PHP_USER_NAME}
chown ${PHP_USER_NAME} /proc/self/fd/{1,2}
chmod 777 -R /app/runtime
chown -R ${PHP_USER_NAME} -R /app/vendor
chown -R ${PHP_USER_NAME} -R /app

su ${PHP_USER_NAME} -c "composer install"
su ${PHP_USER_NAME} -c "php /app/yii migrate --interactive=0"
# Execute CMD
su root -c "service cron start && service supervisor start && tail -f /var/log/syslog"
