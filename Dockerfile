FROM php:7.4-cli-alpine

RUN apk add --no-cache bash

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

CMD ["sleep","infinity"]