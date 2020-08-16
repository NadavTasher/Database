# Select the base image
FROM php:7.4.1-apache
# Enable modifications
RUN a2enmod headers
# Copy frontend & backend to /var/www/html
COPY backend /var/www/html
COPY frontend /var/www/html/manage
# Change ownership & permissions of /opt
RUN chown www-data /opt -R && chmod 775 /opt -R
# Create volume from /opt
VOLUME /opt