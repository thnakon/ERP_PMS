FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    nodejs \
    npm && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Step 1: Install PHP dependencies (using cache)
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts || true

# Step 2: Install Node dependencies and Build Assets (using cache)
COPY package.json package-lock.json ./
RUN npm install

# Step 3: Copy the rest of the application
COPY . .

# Step 4: Finalize Build
RUN npm run build && \
    composer dump-autoload --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/storage

# Expose port
EXPOSE 8000

# Start Laravel and setup DB
CMD php artisan migrate --force && \
    php artisan db:seed --class=RealisticDataSeeder --force && \
    php artisan storage:link && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=8000
