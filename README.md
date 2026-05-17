# Milk Tea Ordering System

A Laravel 11 web-based ordering system for a milk tea shop. The project includes customer ordering, admin product management, MySQL migrations, Eloquent models, validation, and responsive Bootstrap pages.

## Features

### Customer
- Browse products by category and search keyword
- Customize drinks by size, sugar level, ice level, add-ons, and special instructions
- Manage a session-based shopping cart
- Checkout with customer name, contact number, and address
- View order history and order details
- Cancel pending or confirmed orders

### Admin
- Dashboard with order, product, and customer summaries
- Product CRUD with image upload
- Add-on CRUD with image upload
- Size CRUD with price adjustments
- Order list with status and date filters
- Order detail page with customer information and drink customizations
- Update order and payment statuses

### Technical
- Laravel MVC structure
- MySQL database support through Laravel migrations
- Eloquent ORM relationships
- Role-based authentication for admin and customer users
- CSRF protection and server-side form validation
- Default SVG product/add-on images for items without uploads
- Basic PHPUnit health check

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Git

## Installation

1. Open the project folder:

```bash
cd milk-tea-system
```

2. Install dependencies:

```bash
composer install
```

3. Create your environment file:

```bash
cp .env.example .env
```

4. Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=milk_tea_system
DB_USERNAME=root
DB_PASSWORD=
```

5. Generate the app key:

```bash
php artisan key:generate
```

6. Create the database:

```bash
mysql -u root -p -e "CREATE DATABASE milk_tea_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

7. Run migrations and seeders:

```bash
php artisan migrate --seed
```

8. Link public storage for uploaded images:

```bash
php artisan storage:link
```

9. Start the development server:

```bash
php artisan serve
```

Visit `http://localhost:8000`.

## Default Accounts

| Role | Email | Password |
| --- | --- | --- |
| Admin | admin@milktea.test | password123 |
| Customer | customer@example.com | password |

## Verification

Run these before presentation or deployment:

```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
```

## Project Structure

```text
app/Http/Controllers/Admin      Admin dashboard, CRUD, and order management
app/Http/Controllers/Customer   Menu, cart, checkout, and customer orders
app/Models                      Eloquent models and relationships
database/migrations             Database table definitions
database/seeders                Default users, products, sizes, and add-ons
public/images                   Default product and add-on images
resources/views                 Blade templates
routes/web.php                  Web routes
```

## Deployment Notes

For InfinityFree/free shared hosting, use [DEPLOY_INFINITYFREE.md](DEPLOY_INFINITYFREE.md).

- Set `APP_ENV=production` and `APP_DEBUG=false` on the hosting server.
- Point the web root to the Laravel `public` directory.
- Configure the production MySQL credentials in `.env`.
- Run `php artisan key:generate`, `php artisan migrate --seed`, and `php artisan storage:link`.
- Clear and cache configuration when deploying:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

## Final Exam Checklist

- Laravel framework and PHP MVC: implemented
- MySQL integration with migrations: implemented
- Complete CRUD module: implemented for products, add-ons, and sizes
- Form validation and error handling: implemented
- Responsive UI and navigation: implemented with Bootstrap 5
- GitHub repository: use repository name `MilkTea`
- Hosted live URL: add this after deployment
- Documentation: prepare the required template with screenshots and group roles
