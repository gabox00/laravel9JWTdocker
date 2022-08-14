FROM node:14-alpine as frontend

RUN mkdir -p /app/public

COPY package.json webpack.mix.js tailwind.config.js /app/

COPY resources/ /app/resources/

WORKDIR /app

RUN npm install && npm run production

FROM composer:latest as build

WORKDIR /app

COPY composer* /app/

COPY . /app/

RUN composer install

FROM php:8-apache

EXPOSE 80
EXPOSE 443

WORKDIR /app

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=build /app /app
COPY --from=frontend /app/public/js/ /var/www/html/public/js/
COPY --from=frontend /app/public/css/ /var/www/html/public/css/

ARG PASSPORT_PRIV_KEY
ARG PASSPORT_PUB_KEY

RUN echo -e "$PASSPORT_PRIV_KEY" >> /app/storage/oauth-private.key
RUN echo -e "$PASSPORT_PUB_KEY" >> /app/storage/oauth-public.key

COPY /etc/prod/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN chown -R www-data: /app
RUN chmod -R 755 /app/storage
