FROM php:8.2-cli-alpine

WORKDIR /app

# Install MySQLi extension (mysqlnd included in official PHP images)
RUN docker-php-ext-install mysqli

# Copy app files
COPY . /app

# Render provides PORT at runtime; default 10000 for local container runs
ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t public public/index.php"]