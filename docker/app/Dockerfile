FROM alpine:edge
RUN apk add --update --no-cache -X http://dl-cdn.alpinelinux.org/alpine/edge/testing \
  git \
  jq \
  npm \
  php7-ctype \
  php7-curl \
  php7-dom \
  php7-fpm \
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

ADD docker/app/php-fpm.conf /
ADD docker/app/init.sh /sbin/init.sh

ADD protected /project/protected
ADD public /project/public
ADD vendor /project/vendor
ADD node_modules /project/public/node_modules


RUN mkdir -p /run && chown nobody:nobody /run
ARG COMMIT_SHA
RUN echo "${COMMIT_SHA}" > /run/commit_sha

#Mount a tmpfs here
VOLUME /run
ENTRYPOINT ["/sbin/init.sh"]