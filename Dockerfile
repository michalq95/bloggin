FROM bitnami/laravel:10.3.3

WORKDIR /usr/src/app

COPY composer.json ./

RUN composer install 

COPY . ./usr/src/app/

EXPOSE 8081