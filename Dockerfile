# Usamos una imagen base de PHP con Apache
FROM php:8.2-apache

# Instalamos las extensiones necesarias para conectar con MySQL
# Esto es esencial para que funcione la clase Database.php con PDO 
RUN docker-php-ext-install pdo pdo_mysql

# Habilitamos mod_rewrite de Apache (útil para el enrutamiento MVC)
RUN a2enmod rewrite

# Copiamos el código fuente al directorio del servidor (opcional aquí, ya que usamos volúmenes en compose)
WORKDIR /var/www/html