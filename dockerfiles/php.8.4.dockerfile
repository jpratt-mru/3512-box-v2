# https://hub.docker.com/_/php

# Heavily altered from original: https://github.com/sprintcube/docker-compose-lamp/blob/master/bin/php83/Dockerfile

# 💬
# - This is Docker-maintained image (you can tell by format of the image -
#   if it doesn't have a blah/ format, it's a Docker-maintained image).
# - I used 8.4 because it was the most recent stable one at the time
#   of creating this box.
# - These -apache versions are preconfigured with the Apache web server
#   and talk to the server via mod_php vs the newer php-fpm. (https://hub.docker.com/_/php#phpversion-apache)
#   I did this for a number of reasons:
#   - Students do not need the latest and greatest for lab and Projects.
#   - These images were made by folks who know what they're doing and
#     work out of the box.
FROM php:8.4-apache

# 💬
# - COPY: https://docs.docker.com/reference/dockerfile/#copy
# - The www refers to the www directory at the root of this project.
# - This will move the public directory (the Apache document root) to
#   /var/www/html.
# - Everything is copied over as root; on a production server, we
#   wouldn't do this, but getting around it is complicated and not
#   worth the time here.
COPY www /var/www/html

# 💬
# - The config refers to the config directory at the root of this project.
# - default.conf provides useful settings for our site, and the file
#   needs to be copied to the container before it spins up so that
#   Apache detects it!
COPY config/vhosts/default.conf /etc/apache2/sites-enabled

# 💬 
# - Installs pdo_mysql so devs can use PDO in their PHP code.
# - Installs xdebug, so that students can use VS Code's PHP Debug extenstion
#   to do "real" debugging. If they want.
# - We use a single RUN and the &&'s' so that all this installation 
#   happens in one Docker layer, which apparently is a good thing. (https://docs.docker.com/build/building/best-practices/#minimize-the-number-of-layers)
RUN docker-php-ext-install pdo_mysql && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    mkdir /var/log/xdebug


# 💬
# I'll keep these in here in case I get the energy to figure out
# how to get SSL working...it's totally not a deal breaker, so....
# Insure an SSL directory exists
# RUN mkdir -p /etc/apache2/ssl
# Enable SSL support
# RUN a2enmod ssl && a2enmod rewrite

# 💬 
# - Enables the mod_rewrite (https://httpd.apache.org/docs/current/mod/mod_rewrite.html)
#   and mod_headers (https://httpd.apache.org/docs/current/mod/mod_headers.html) modules.
# - These were in the original sprintcube Dockerfile; I don't actually think
#   they're needed here, but the command runs quickly.
RUN a2enmod rewrite headers

# 💬
# There's a bit of crud left over from the process, so remove it
# to make the image size a bit smaller.
RUN rm -rf /usr/src/*
