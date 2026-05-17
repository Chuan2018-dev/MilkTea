# Milk Tea Ordering System - Installation Guide

## Complete Installation Commands

### Step 1: Create Project Directory and Copy Files

```bash
mkdir milk-tea-system
cd milk-tea-system
# Copy all project files to this directory
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Environment Setup

```bash
cp .env.example .env
```

Edit `.env` file:

```env
APP_NAME="Milk Tea Ordering System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=milk_tea_system
DB_USERNAME=root
DB_PASSWORD=your_mysql_password

SESSION_DRIVER=file
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Create Database

```bash
mysql -u root -p -e "CREATE DATABASE milk_tea_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 6: Run Migrations and Seeders

```bash
php artisan migrate --seed
```

### Step 7: Create Storage Symlink

```bash
php artisan storage:link
```

### Step 8: Start Development Server

```bash
php artisan serve
```

Access the application at: `http://localhost:8000`

---

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@milktea.test | password123 |
| **Customer** | customer@example.com | password |

---

## File Structure Summary

### Configuration Files
- `composer.json` - PHP dependencies
- `.env.example` - Environment template
- `config/app.php` - App configuration
- `config/auth.php` - Auth configuration
- `config/database.php` - Database configuration
- `config/session.php` - Session configuration

### Bootstrap Files (Laravel 11)
- `bootstrap/app.php` - Application bootstrap with middleware alias
- `bootstrap/providers.php` - Service providers

### Middleware
- `app/Http/Middleware/AdminMiddleware.php` - Admin access control

### Models
- `app/Models/User.php` - User model with role support
- `app/Models/Product.php` - Product model
- `app/Models/Size.php` - Size model
- `app/Models/AddOn.php` - Add-on model
- `app/Models/Order.php` - Order model
- `app/Models/OrderItem.php` - Order item model

### Controllers

#### Auth Controllers
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/Auth/PasswordResetLinkController.php`
- `app/Http/Controllers/Auth/NewPasswordController.php`
- `app/Http/Controllers/Auth/EmailVerificationPromptController.php`
- `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`
- `app/Http/Controllers/Auth/VerifyEmailController.php`
- `app/Http/Controllers/Auth/PasswordController.php`

#### Customer Controllers
- `app/Http/Controllers/Customer/DashboardController.php`
- `app/Http/Controllers/Customer/MenuController.php`
- `app/Http/Controllers/Customer/CartController.php`
- `app/Http/Controllers/Customer/CheckoutController.php`
- `app/Http/Controllers/Customer/OrderController.php`

#### Admin Controllers
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/ProductController.php`
- `app/Http/Controllers/Admin/AddOnController.php`
- `app/Http/Controllers/Admin/SizeController.php`
- `app/Http/Controllers/Admin/OrderController.php`

#### Other Controllers
- `app/Http/Controllers/Controller.php`
- `app/Http/Controllers/ProfileController.php`

### Requests
- `app/Http/Requests/Auth/LoginRequest.php`
- `app/Http/Requests/ProfileUpdateRequest.php`

### Migrations
- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/0001_01_01_000001_create_cache_table.php`
- `database/migrations/0001_01_01_000002_create_jobs_table.php`
- `database/migrations/2024_01_01_000003_create_products_table.php`
- `database/migrations/2024_01_01_000004_create_sizes_table.php`
- `database/migrations/2024_01_01_000005_create_add_ons_table.php`
- `database/migrations/2024_01_01_000006_create_orders_table.php`
- `database/migrations/2024_01_01_000007_create_order_items_table.php`
- `database/migrations/2024_01_01_000008_create_order_item_add_on_table.php`

### Seeders
- `database/seeders/DatabaseSeeder.php`
- `database/seeders/UserSeeder.php`
- `database/seeders/SizeSeeder.php`
- `database/seeders/AddOnSeeder.php`
- `database/seeders/ProductSeeder.php`

### Factories
- `database/factories/UserFactory.php`

### Routes
- `routes/web.php` - All application routes

### Views

#### Layouts
- `resources/views/layouts/app.blade.php`

#### Auth Views
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/auth/verify-email.blade.php`

#### Customer Views
- `resources/views/customer/dashboard.blade.php`
- `resources/views/customer/menu/index.blade.php`
- `resources/views/customer/menu/show.blade.php`
- `resources/views/customer/cart/index.blade.php`
- `resources/views/customer/checkout/index.blade.php`
- `resources/views/customer/orders/index.blade.php`
- `resources/views/customer/orders/show.blade.php`

#### Admin Views
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/products/index.blade.php`
- `resources/views/admin/products/create.blade.php`
- `resources/views/admin/products/edit.blade.php`
- `resources/views/admin/addons/index.blade.php`
- `resources/views/admin/addons/create.blade.php`
- `resources/views/admin/addons/edit.blade.php`
- `resources/views/admin/sizes/index.blade.php`
- `resources/views/admin/sizes/create.blade.php`
- `resources/views/admin/sizes/edit.blade.php`
- `resources/views/admin/orders/index.blade.php`
- `resources/views/admin/orders/show.blade.php`

#### Profile Views
- `resources/views/profile/edit.blade.php`

### Public Files
- `public/index.php`
- `public/.htaccess`

### Other Files
- `artisan` - Laravel CLI
- `README.md` - Project documentation
- `INSTALL.md` - This installation guide

---

## Key Implementation Details

### Middleware Alias Registration (bootstrap/app.php)

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => AdminMiddleware::class,
    ]);
})
```

### AdminMiddleware

Located at `app/Http/Middleware/AdminMiddleware.php`:

```php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (!auth()->user()->isAdmin()) {
        abort(403, 'Unauthorized access. Admin privileges required.');
    }

    return $next($request);
}
```

### User Model Role Check

Located at `app/Models/User.php`:

```php
public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isCustomer(): bool
{
    return $this->role === 'customer';
}
```

---

## Troubleshooting

### Issue: Storage images not showing
**Solution:** Run `php artisan storage:link`

### Issue: Permission denied on storage
**Solution:** 
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Issue: Session not working
**Solution:** Ensure `SESSION_DRIVER=file` in `.env`

### Issue: Database connection failed
**Solution:** Check DB credentials in `.env` file

---

## Routes Summary

### Public Routes
- `GET /` - Redirects to menu

### Auth Routes
- `GET /login` - Login page
- `POST /login` - Login action
- `GET /register` - Register page
- `POST /register` - Register action
- `POST /logout` - Logout

### Customer Routes (requires auth)
- `GET /dashboard` - Redirects based on role
- `GET /customer/dashboard` - Customer dashboard
- `GET /customer/orders` - Order history
- `GET /customer/orders/{order}` - Order details
- `POST /customer/orders/{order}/cancel` - Cancel order

### Menu Routes
- `GET /menu` - Browse menu
- `GET /menu/{product}` - Product detail/customization

### Cart Routes
- `GET /cart` - View cart
- `POST /cart` - Add to cart
- `PATCH /cart/{key}` - Update quantity
- `DELETE /cart/{key}` - Remove item
- `DELETE /cart` - Clear cart

### Checkout Routes
- `GET /checkout` - Checkout page
- `POST /checkout` - Place order

### Admin Routes (requires admin)
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/products` - Product list
- `GET /admin/products/create` - Create product
- `POST /admin/products` - Store product
- `GET /admin/products/{product}/edit` - Edit product
- `PUT /admin/products/{product}` - Update product
- `DELETE /admin/products/{product}` - Delete product
- `GET /admin/addons` - Add-on list
- `GET /admin/addons/create` - Create add-on
- `POST /admin/addons` - Store add-on
- `GET /admin/addons/{addOn}/edit` - Edit add-on
- `PUT /admin/addons/{addOn}` - Update add-on
- `DELETE /admin/addons/{addOn}` - Delete add-on
- `GET /admin/sizes` - Size list
- `GET /admin/sizes/create` - Create size
- `POST /admin/sizes` - Store size
- `GET /admin/sizes/{size}/edit` - Edit size
- `PUT /admin/sizes/{size}` - Update size
- `DELETE /admin/sizes/{size}` - Delete size
- `GET /admin/orders` - Order list
- `GET /admin/orders/{order}` - Order details
- `PATCH /admin/orders/{order}/status` - Update status
- `PATCH /admin/orders/{order}/payment` - Update payment status
