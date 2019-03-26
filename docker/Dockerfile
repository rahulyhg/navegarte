FROM php:7.2-apache

# Atualiza container e instala algumas dependencias
RUN  apt-get update \
  && apt-get upgrade -y \
  && apt-get autoremove -y \
  && apt-get install -yq \
    libzip-dev \
    zlib1g-dev \
    unixodbc \
    unixodbc-dev \
    freetds-dev \
    freetds-bin \
    tdsodbc \
    libmcrypt-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libxslt-dev \
    #gulp-notify
    libnotify-bin \
    mysql-client \
    curl \
    git \
    vim \
    nano \
    sudo \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# Instala dependencia do php
RUN docker-php-ext-configure \
    gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install \
    gd \
    json \
    mbstring \
    pdo \
    pdo_mysql \
    mysqli \
    zip \
    opcache \
    intl \
    xsl \
    exif

# Configura rar
RUN pecl -v install rar
RUN docker-php-ext-enable rar

# Configura pdo_dblib
RUN docker-php-ext-configure pdo_dblib --with-libdir=/lib/x86_64-linux-gnu

RUN docker-php-ext-install pdo_dblib
RUN docker-php-ext-enable pdo_dblib

# Instala xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Instala nodejs, npm, bower, gulp
# RUN curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
# RUN apt-get install -y nodejs
# RUN npm update -g npm
# RUN npm cache verify
# RUN npm cache clean --force
# RUN npm install -g sass
# RUN npm install -g bower
# RUN npm install -g gulp
# RUN npm install -g gulp-notify
# RUN npm install -g notify-send
# RUN npm install -g coffeescript
# RUN npm install -g browser-sync

# Instala composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "copy('https://composer.github.io/installer.sig', 'installer.sig');" \
  && php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); unlink('installer.sig');} echo PHP_EOL;" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && php -r "unlink('composer-setup.php'); unlink('installer.sig');"

# Configura timezone padrão
RUN echo "America/Sao_Paulo" > /etc/timezone && \
    dpkg-reconfigure -f noninteractive tzdata

# Habilita modulos do apache
RUN a2enmod rewrite
RUN a2enmod ssl

# Cria certificado SSL
RUN openssl req -new -x509 -days 365 -nodes -out /etc/ssl/certs/ssl-cert-snakeoil.pem -keyout /etc/ssl/private/ssl-cert-snakeoil.key -subj "/C=BR/ST=Paraná/L=Umuarama/O=VCWeb Networks/CN=*.localhost"

# Habilita o certificado SSL
RUN a2ensite default-ssl

# Permissão de pasta
# RUN chown -R www-data:www-data /var/www

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
