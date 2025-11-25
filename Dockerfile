FROM php:8.2-alpine

WORKDIR /app

# Copy your PHP files
COPY . .

# Expose the port Render uses
EXPOSE 10000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "getRandom.php"]