FROM alpine
RUN apk add --no-cache --update \
  git \
  tini \
  sassc \
  php7 \
  php7-ctype \
  php7-curl \
  php7-dom \
  php7-phpdbg \
  php7-gd \
  php7-intl \
  php7-json \
  php7-mbstring \
  php7-pdo_mysql \
  php7-phar \
  php7-posix \
  php7-session \
  php7-tokenizer \
  php7-xml \
  php7-xmlwriter \
  php7-zip \
  --
ADD wait.sh /bin/wait-for-it
ADD init.sh /sbin/init.sh
ENV COMPOSER_CACHE_DIR=/composer-cache
ENV COMPOSER_HOME=/composer
RUN mkdir $COMPOSER_CACHE_DIR && \
    mkdir $COMPOSER_HOME && \
    php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" && \
    php -r "if (hash_file('sha384', '/tmp/composer-setup.php') === file_get_contents('https://composer.github.io/installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php /tmp/composer-setup.php --install-dir /bin --filename composer && \
    php -r "unlink('/tmp/composer-setup.php');" && \
    composer global require hirak/prestissimo && \
    chmod -R 777 $COMPOSER_CACHE_DIR && \
    chmod -R 777 $COMPOSER_HOME
VOLUME /composer-cache
ENTRYPOINT ["/sbin/init.sh"]