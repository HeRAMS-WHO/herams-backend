FROM alpine:edge
RUN apk add --update --no-cache -X http://dl-cdn.alpinelinux.org/alpine/edge/testing \
  git \
  wait4ports \
  jq \
  npm \
  php7-ctype \
  php7-curl \
  php7-dom \
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
  php7-pecl-xlswriter \
  php7-pecl-pcov \
  --
# Install composer
RUN EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)" && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" && \
    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then exit 1; fi && \
    php composer-setup.php --filename composer --install-dir /bin && \
    php -r "unlink('composer-setup.php');"


ADD php-fpm.conf /
ADD init.sh /sbin/init.sh
RUN mkdir -p /run && chown nobody:nobody /run

#Mount a tmpfs here
VOLUME /run
ENTRYPOINT ["/sbin/init.sh"]
