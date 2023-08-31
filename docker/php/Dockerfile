FROM php:8.1-fpm-alpine
LABEL org.opencontainers.image.source = "https://github.com/HeRAMS-WHO/docker"
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN apk add --update --no-cache \
  git \
  wait4ports \
  jq \
  npm
RUN install-php-extensions \
  @composer \
  ctype \
  curl \
  dom \
  gd \
  intl \
  json \
  mbstring \
  openssl \
  pcov \
  pdo_mysql \
  phar \
  posix \
  session \
  simplexml \
  sodium \
  tokenizer \
  xdebug \
  xlswriter \
  xml \
  xmlwriter \
  zip
ADD php-fpm.conf /
ADD xdebug.ini 	/usr/local/etc/php/conf.d/50_xdebug.ini
ADD init.sh /sbin/init.sh
RUN mkdir -p /run && chown nobody:nobody /run
RUN echo "${COMMIT_SHA}" > /commit_sha
#Mount a tmpfs here
VOLUME /run
ENTRYPOINT ["/sbin/init.sh"]
