FROM webdevops/php-apache:7.4

ENV TZ=Asia/Jakarta
RUN apt-get update && \
    apt-get install -yq tzdata && \
    ln -fs /usr/share/zoneinfo/Asia/Jakarta /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata
RUN apt-get install -y npm 

WORKDIR /app

RUN php artisan migrate:fresh
RUN php artisan db:seed
RUN php artisan key:generate

RUN useradd -rm -d /app -s /bin/bash -g root -G sudo -u 1001 ubuntu
USER ubuntu