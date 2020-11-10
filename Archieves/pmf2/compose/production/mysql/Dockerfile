FROM mysql:5.7

COPY ./compose/production/mysql/conf.d /etc/mysql/conf.d

COPY ./compose/production/mysql/docker-healthcheck /usr/local/bin/

HEALTHCHECK CMD ["docker-healthcheck"]
