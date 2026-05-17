FROM php:8.3-cli

WORKDIR /app

COPY frontend-laravel .

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "router.php"]
