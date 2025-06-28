<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

---

## ⚙️ Установка и запуск проекта

1. Скопируйте файл `.env`:
    ```bash
    cp .env.example .env
    ```

2. Убедитесь, что в `.env` указано:
    ```
    QUEUE_CONNECTION=database
    ```

3. Сгенерируйте ключ приложения:
    ```bash
    php artisan key:generate
    ```

4. Установите зависимости:
    ```bash
    composer install
    npm install
    npm run dev
    ```

5. Выполните миграции базы данных:
    ```bash
    php artisan migrate
    ```

6. Запустите локальный сервер:
    ```bash
    php artisan serve
    ```

7. В отдельном терминале запустите обработку очередей:
    ```bash
    php artisan queue:work
    ```

8. Для создания пользователя используйте:
    ```bash
    php artisan user:create "Ivan Ivanov" ivan@example.com ivan123
    ```

9. Для начисления средств:
    ```bash
    php artisan balance:process ivan@example.com 100 "Начисление"
    ```

10. Для списания средств:
    ```bash
    php artisan balance:process ivan@example.com 50 "Оплата подписки" --debit
    ```

---

## ✅ Полезные команды

- Полная очистка и пересоздание базы данных:
    ```bash
    php artisan migrate:fresh --seed
    ```

---

## 🔐 Лицензия

Проект использует фреймворк Laravel, лицензированный под [MIT license](https://opensource.org/licenses/MIT).
