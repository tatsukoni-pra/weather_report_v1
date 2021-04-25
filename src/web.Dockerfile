FROM nginx:1.19.6-alpine

# unixソケット用のユーザを追加
RUN adduser -S www-data -G www-data -u 82 && \
    echo "www-data ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers && \
    echo 'www-data:www-data' | chpasswd

COPY docker/web/default.conf /etc/nginx/nginx.conf

# unix socket
RUN mkdir /var/run/php-fpm
VOLUME ["/var/run/php-fpm"]
