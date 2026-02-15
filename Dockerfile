FROM php:8.2-cli-alpine

WORKDIR /app

# Install PostgreSQL PDO extension
RUN docker-php-ext-install pdo_pgsql

# Copy app files
COPY . /app

# Render provides PORT at runtime; default 10000 for local container runs
ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t public public/index.php"]
