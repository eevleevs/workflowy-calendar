FROM php:apache
WORKDIR /var/www/html
RUN apt update
RUN apt install -y git
RUN git clone https://github.com/johansatge/workflowy-php.git
COPY index.php .
