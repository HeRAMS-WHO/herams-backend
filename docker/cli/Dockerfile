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
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --filename composer --install-dir /bin && \
    php -r "unlink('composer-setup.php');" && \
    composer global require hirak/prestissimo && \
    chmod -R 777 $COMPOSER_CACHE_DIR && \
    chmod -R 777 $COMPOSER_HOME
VOLUME /composer-cache
ENTRYPOINT ["/sbin/init.sh"]