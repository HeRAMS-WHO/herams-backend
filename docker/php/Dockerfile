FROM alpine:edge
RUN apk add --update --no-cache -X http://dl-cdn.alpinelinux.org/alpine/edge/testing \
  git \
  wait4ports \
  jq \
  npm \
  php8-cli \
  php8-ctype \
  php8-curl \
  php8-dom \
  php8-fpm \
  php8-gd \
  php8-intl \
  php8-json \
  php8-mbstring \
  php8-openssl \
  php8-pdo_mysql \
  php8-phar \
  php8-posix \
  php8-session \
  php8-sodium \
  php8-tokenizer \
  php8-xml \
  php8-simplexml \
  php8-xmlwriter \
  php8-zip \
  php8-pecl-xlswriter \
  php8-pecl-pcov \
  php8-pecl-xdebug \
  --
# Install composer
COPY --from=composer/composer /usr/bin/composer /usr/bin/composer
ADD php-fpm.conf /
ADD xdebug.ini /etc/php8/conf.d/50_xdebug.ini
ADD init.sh /sbin/init.sh
RUN mkdir -p /run && chown nobody:nobody /run

#Mount a tmpfs here
VOLUME /run
ENTRYPOINT ["/sbin/init.sh"]
