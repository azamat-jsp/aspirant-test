Инструкция для деплоя
--------------

- Убедитесь что база данных создана
- php doctrine orm:schema-tool:create
- composer install

Для запуска в локальном сервере
 - cd public/
 - php -S localhost:8000
 - настроки для базы находятся в разделе connection в settings.php

Есть 2 страницы
 - Общий список трейлеров
 - Подробная страница
