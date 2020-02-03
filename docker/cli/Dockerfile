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
  php7-simplexml \
  php7-xmlwriter \
  php7-zip \
  --
RUN apk add --no-cache php7-pear php7-dev build-base \
    && pecl install pcov \
    && apk del php7-pear php7-dev build-base

# Add php.ini directives.
RUN echo $'memory_limit=1G \n\
\n\
variables_order = "GPCSE" \n\
\n\
extension = pcov.so \n\
' >> /etc/php7/php.ini
ADD wait.sh /bin/wait-for-it
ADD init.sh /sbin/init.sh
ENV COMPOSER_CACHE_DIR=/composer-cache
ENV COMPOSER_HOME=/composer
RUN mkdir $COMPOSER_CACHE_DIR && \
    mkdir $COMPOSER_HOME && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'c5b9b6d368201a9db6f74e2611495f369991b72d9c8cbd3ffbc63edff210eb73d46ffbfce88669ad33695ef77dc76976') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --filename composer --install-dir /bin && \
    php -r "unlink('composer-setup.php');" && \
    composer global require hirak/prestissimo && \
    chmod -R 777 $COMPOSER_CACHE_DIR && \
    chmod -R 777 $COMPOSER_HOME
VOLUME /composer-cache
ENTRYPOINT ["/sbin/init.sh"]