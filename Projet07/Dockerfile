FROM debian:bookworm-slim

RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
       nginx php-fpm php-cli ca-certificates \
    && rm -rf /var/lib/apt/lists/*

COPY default.conf /etc/nginx/sites-available/default
COPY index.html /usr/share/nginx/html/index.html
COPY save.php /usr/share/nginx/html/save.php
COPY results.json /usr/share/nginx/html/results.json

RUN chown -R www-data:www-data /usr/share/nginx/html

EXPOSE 80

CMD ["sh", "-c", "php-fpm8.2 -D; nginx -g 'daemon off;'"]
