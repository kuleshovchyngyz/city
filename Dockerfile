FROM php:7.4-fpm

# Update packages and install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    git \
    curl \
    libmcrypt-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath soap gd

# Set working directory
WORKDIR /var/www/html

# Copy application files to the container
COPY . .

