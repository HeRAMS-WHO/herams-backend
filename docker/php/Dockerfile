FROM php:7.4-fpm-alpine
RUN php --version && sleep 10
RUN apk add --update --no-cache git jq wait4ports npm
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions \
  ctype \
  curl \
  dom \
  gd \
  intl \
  json \
  mbstring \
  pdo_mysql \
  phar \
  posix \
  session \
  tokenizer \
  xml \
  simplexml \
  xmlwriter \
  zip \
  xlswriter \
  pcov \
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
