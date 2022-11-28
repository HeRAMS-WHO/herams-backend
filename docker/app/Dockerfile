FROM ghcr.io/herams-who/docker/phpfpm:latest
RUN apk add --no-cache dumb-init jq
ADD docker/app/php-fpm.conf /
ADD docker/app/init.sh /sbin/init.sh

ADD protected /project/protected
ADD public /project/public
ADD vendor /project/vendor
ADD node_modules /project/public/node_modules

ADD tests/_data/db /database
ARG INCLUDE_DATABASE_SEED
RUN sh -c '[ "${INCLUDE_DATABASE_SEED}" == "true" ] || rm -rf /database'
RUN mkdir -p /run && chown nobody:nobody /run
ARG COMMIT_SHA
RUN echo "${COMMIT_SHA}" > /run/commit_sha

#Mount a tmpfs here
VOLUME /run
ENTRYPOINT ["/usr/bin/dumb-init", "-c", "-r", "15:3", "--", "/sbin/init.sh"]
