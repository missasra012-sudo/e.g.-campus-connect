FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip curl git

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install

RUN touch database/database.sqlite && php artisan migrate --force 

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000