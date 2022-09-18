FROM php:8.1.4-apache

ENV TZ=Australia/Sydney
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN echo "date.timezone=$TZ" >> /usr/local/etc/php/conf.d/default.ini

RUN apt-get update && apt-get install -y --fix-missing apt-utils gnupg zip unzip\ 
	&& echo "deb http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list \
	&& echo "deb-src http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list \
	&& apt-get install -y zlib1g-dev libzip-dev librabbitmq-dev \
	&& docker-php-ext-install zip pdo pdo_mysql\
	&& pecl channel-update pecl.php.net \
	&& pecl install amqp xdebug\
	&& docker-php-ext-enable amqp \
	&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

#ENTRYPOINT php -d memory_limit=-1 /usr/local/bin/composer install -vvv
