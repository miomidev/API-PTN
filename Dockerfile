FROM php:8.2-apache

# Install git untuk auto-deploy (webhook git pull)
RUN apt-get update && apt-get install -y git && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set permission & working directory
WORKDIR /var/www/html

# Copy semua source code
COPY . /var/www/html/

# Set proper ownership
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (internal container)
EXPOSE 80
