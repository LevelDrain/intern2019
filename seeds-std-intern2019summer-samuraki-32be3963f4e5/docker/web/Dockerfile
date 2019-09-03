ARG PHP_VERSION=7.2
FROM php:${PHP_VERSION}-apache
LABEL maintaner="Kazuki Kinugasa <kinugasa@seeds-std.co.jp>"
ARG SERVER_NAME=localhost
ENV SERVER_NAME $SERVER_NAME
COPY 001-apache.conf /etc/apache2/sites-available/


####
# package
####
RUN apt-get update \
    && apt-get -y -qq -y --no-install-recommends install wget lsb-release libicu-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev locales syslog-ng libicu-dev libmcrypt-dev libsodium-dev \
    && apt-get -qq clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install mysqli pdo pdo_mysql intl gd zip mbstring fileinfo

RUN pecl install mcrypt-1.0.1
RUN pecl install libsodium
RUN docker-php-ext-enable mcrypt

####
# setting
####

# locale
RUN locale-gen ja_JP.UTF-8
ENV LANG ja_JP.UTF-8
ENV LC_CTYPE ja_JP.UTF-8
RUN localedef -f UTF-8 -i ja_JP ja_JP.utf8

# timezone設定
RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

####
# postfix
####
RUN echo mail > /etc/hostname
#ADD etc-hosts.txt /etc/hosts
#RUN chown root:root /etc/hosts
# Install Postfix.
RUN echo "postfix postfix/main_mailer_type string Internet site" > preseed.txt
RUN echo "postfix postfix/mailname string mail.example.com" >> preseed.txt
# Use Mailbox format.
RUN debconf-set-selections preseed.txt
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -q -y postfix \
    && apt-get -qq clean \
    && rm -rf /var/lib/apt/lists/*
RUN postconf -e myhostname=mail.example.com
RUN postconf -e mydestination="mail.example.com, example.com, localhost.localdomain, localhost"
RUN postconf -e mail_spool_directory="/var/spool/mail/"
RUN postconf -e mailbox_command=""
# Add a local user to receive mail at someone@example.com, with a delivery directory
# (for the Mailbox format).
RUN useradd -s /bin/bash someone
RUN mkdir /var/spool/mail/someone
RUN chown someone:mail /var/spool/mail/someone
ADD etc-aliases.txt /etc/aliases
RUN chown root:root /etc/aliases
RUN newaliases
# Use syslog-ng to get Postfix logs (rsyslog uses upstart which does not seem
# to run within Docker).
expose 25

####
# apache mod
####

RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
RUN ln -s /etc/apache2/mods-available/vhost_alias.load /etc/apache2/mods-enabled
RUN echo ServerName $SERVER_NAME > /etc/apache2/conf-available/fqdn.conf && a2enconf fqdn
RUN a2dissite 000-default \
  && a2ensite 001-apache

####
# COMMAND
####

#cmd ["bash", "-c", "service syslog-ng start ; service postfix start ;docker-php-entrypoint apache2-foreground"]
