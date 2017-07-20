# deputados-api

A API é baseada no micro-framework Lumen e estrutura de banco de dados pre-modelada para MySQL com arquivo compatível para leitura no MySQL Workbench.

## Como Executar
Execute o script SQL para criar o banco de dados e tabelas que serão utilizados pela API. Você pode encontrar o Script em doc/create_database_tables.sql

Renomeie o arquivo .env.example para .env e adicione suas configurações de banco de dados

    APP_ENV=local
    APP_DEBUG=true
    APP_KEY=
    APP_TIMEZONE=UTC

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=op_camara
    DB_USERNAME=<usuario>
    DB_PASSWORD=<senha>

    CACHE_DRIVER=memcached
    QUEUE_DRIVER=sync

Abra o terminal na raiz do projeto e execute os seguintes comandos

    # baixar as dependencias
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    php composer.phar install

    # rodar o projeto
    php -S localhost:8000 -t public

    # seed dos dados diretamente da camara dos deputados
    curl -X GET "http://localhost:8000/seed/deputados" -H  "accept: application/json"

    curl -X GET "http://localhost:8000/seed/despesas" -H  "accept: application/json"

## Vulnerabilidades e Segurança

Se você descobriu alguma brecha, bug ou erro entre em contato por e-mail. Escreva para victorximenis@gmail.com informando a descoberta.

## Licença

Este projeto é open-source. A licença pode ser encontrada em [Licença GPL](https://github.com/OsPoliticosPontoCom/deputados-api/blob/master/LICENSE)
