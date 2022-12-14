FROM ghcr.io/herams-who/docker/phpfpm:latest
RUN apk add --no-cache dumb-init jq
ADD docker/api/php-fpm.conf /
ADD docker/api/init.sh /sbin/init.sh

ADD microservices /project
RUN mkdir -p /project/public && ln -s /project/api/src/index.php /project/public/index.php
RUN mkdir -p /run && chown nobody:nobody /run
RUN mkdir -p /shared && chown nobody:nobody /shared
ARG COMMIT_SHA
RUN echo "${COMMIT_SHA}" > /run/commit_sha

#Mount a tmpfs here
VOLUME /run
RUN ["php-fpm", "-t", "-y", "/php-fpm.conf"]
ENTRYPOINT ["/usr/bin/dumb-init", "-c", "-r", "15:3", "--", "/sbin/init.sh"]
