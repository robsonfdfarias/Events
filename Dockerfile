# Usar uma imagem oficial do PHP com Nginx como base
FROM php:8.2-fpm

# Instalar dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \ 
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    libpng16-16 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir o diretório de trabalho
WORKDIR /var/www

RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www


# Copiar o código da aplicação Laravel para dentro do container
COPY . .

RUN git config --global --add safe.directory /var/www

# Instalar dependências do Laravel (usando o Composer)
# RUN composer install --no-dev --optimize-autoloader
RUN rm -rf vendor && composer install --no-dev --optimize-autoloader


# Configurar permissões adequadas para as pastas de storage e cache
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expôr a porta 9000 para o container
EXPOSE 9000

# Comando para rodar o servidor PHP FPM
CMD ["php-fpm"]
