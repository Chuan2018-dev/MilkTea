# Milk Tea Ordering Website

A complete web-based Milk Tea Ordering System built with Laravel 11, PHP, MySQL, Blade, and Bootstrap 5. This system features a customer-facing storefront and an admin dashboard for managing products, add-ons, and orders.

## Features

### Customer Features
- Browse milk tea products with images and descriptions
- Filter products by category (Milk Tea, Fruit Tea, Smoothies, Coffee)
- Search products by name
- Customize orders with:
  - Size options (Small, Medium, Large)
  - Sugar levels (0%, 30%, 50%, 70%, 100%)
  - Ice levels (No Ice, Less, Regular, Extra)
  - Add-ons (Tapioca Pearls, Pudding, Grass Jelly, etc.)
- Session-based shopping cart
- Checkout with multiple payment methods (Cash, Card, E-Wallet)
- View order history and track order status
- Cancel pending orders

### Admin Features
- Dashboard with statistics and charts
- Product management (CRUD with image upload)
- Add-on management (CRUD with image upload)
- Order management with status updates
- Print order receipts
- View sales reports and analytics

### Android APK App
- Native Android project included at `android-app/`
- Connects to your online Laravel site URL
- Supports login sessions, pull-to-refresh, and form file uploads
- Can be built to APK using Android Studio

## System Requirements

- PHP >= 8.2
- MySQL >= 5.7 or MariaDB >= 10.3
- Composer >= 2.0
- Node.js >= 18.x (optional, for asset compilation)

## Installation Instructions

### Step 1: Clone or Download the Project

```bash
cd /path/to/your/web/directory
# Copy the milk-tea-shop folder to your web directory
```

### Step 2: Install Dependencies

```bash
cd milk-tea-shop
composer install
```

### Step 3: Create Environment File

```bash
cp .env.example .env
```

Edit the `.env` file and configure your database settings:

```env
APP_NAME="Milk Tea Shop"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=milktea_shop
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Create Database

Create a new MySQL database:

```sql
CREATE DATABASE milktea_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Run Seeders

```bash
php artisan db:seed
```

This will create:
- Default admin account: `admin@milkteashop.com` / `password`
- Sample customer accounts: `john@example.com` / `password`, `jane@example.com` / `password`
- 15 sample products across different categories
- 10 sample add-ons

### Step 8: Create Storage Link

```bash
php artisan storage:link
```

### Step 9: Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Android APK Build

The Android app project is in:

```bash
milk-tea-shop/android-app
```

### Quick Steps

1. Open `android-app/app/build.gradle`
2. Set your deployed backend URL in:
   ```groovy
   buildConfigField "String", "BASE_URL", "\"https://your-domain.com\""
   ```
3. Open `android-app` in Android Studio
4. Build APK:
   - `Build` > `Build Bundle(s) / APK(s)` > `Build APK(s)`
5. Output APK:
   - `android-app/app/build/outputs/apk/debug/app-debug.apk`

For full details, see:

```bash
android-app/README.md
```

## Default Login Credentials

### Admin Account
- **Email:** admin@milkteashop.com
- **Password:** password

### Customer Accounts
- **Email:** john@example.com
- **Password:** password

- **Email:** jane@example.com
- **Password:** password

## Project Structure

```
milk-tea-shop/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── Admin/
│   │   │   │   ├── AdminController.php
│   │   │   │   ├── AdminProductController.php
│   │   │   │   ├── AdminAddonController.php
│   │   │   │   └── AdminOrderController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   └── OrderController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── CustomerMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Addon.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/
│   ├── app.php
│   └── providers.php
├── config/
│   └── app.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2024_01_15_000001_create_products_table.php
│   │   ├── 2024_01_15_000002_create_addons_table.php
│   │   ├── 2024_01_15_000003_create_orders_table.php
│   │   └── 2024_01_15_000004_create_order_items_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── ProductSeeder.php
│       └── AddonSeeder.php
├── public/
│   ├── css/
│   │   ├── app.css
│   │   └── admin.css
│   ├── js/
│   │   ├── app.js
│   │   └── admin.js
│   └── images/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── admin.blade.php
│       ├── partials/
│       │   ├── navbar.blade.php
│       │   ├── footer.blade.php
│       │   ├── alerts.blade.php
│       │   ├── admin-sidebar.blade.php
│       │   └── admin-navbar.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── customer/
│       │   ├── home.blade.php
│       │   ├── about.blade.php
│       │   ├── contact.blade.php
│       │   ├── products/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── cart/
│       │   │   └── index.blade.php
│       │   └── orders/
│       │       ├── index.blade.php
│       │       ├── show.blade.php
│       │       └── checkout.blade.php
│       └── admin/
│           ├── dashboard.blade.php
│           ├── products/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           ├── addons/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           └── orders/
│               ├── index.blade.php
│               ├── show.blade.php
│               └── print.blade.php
├── routes/
│   └── web.php
├── composer.json
├── artisan
└── README.md
```

## Routes

### Public Routes
- `GET /` - Home page
- `GET /about` - About page
- `GET /contact` - Contact page
- `GET /menu` - Product listing
- `GET /menu/{product}` - Product details

### Authentication Routes
- `GET /login` - Login form
- `POST /login` - Login action
- `GET /register` - Registration form
- `POST /register` - Registration action
- `POST /logout` - Logout

### Customer Routes (Authenticated)
- `GET /cart` - View cart
- `POST /cart/add` - Add to cart
- `PATCH /cart/{key}` - Update cart item
- `DELETE /cart/{key}` - Remove from cart
- `DELETE /cart` - Clear cart
- `GET /orders` - Order history
- `GET /orders/checkout` - Checkout page
- `POST /orders` - Place order
- `GET /orders/{order}` - Order details
- `PATCH /orders/{order}/cancel` - Cancel order

### Admin Routes (Authenticated + Admin)
- `GET /admin` - Admin dashboard
- `GET /admin/products` - Product list
- `GET /admin/products/create` - Create product form
- `POST /admin/products` - Store product
- `GET /admin/products/{product}/edit` - Edit product form
- `PUT /admin/products/{product}` - Update product
- `DELETE /admin/products/{product}` - Delete product
- `PATCH /admin/products/{product}/toggle-status` - Toggle product status
- `GET /admin/addons` - Add-on list
- `GET /admin/addons/create` - Create add-on form
- `POST /admin/addons` - Store add-on
- `GET /admin/addons/{addon}/edit` - Edit add-on form
- `PUT /admin/addons/{addon}` - Update add-on
- `DELETE /admin/addons/{addon}` - Delete add-on
- `PATCH /admin/addons/{addon}/toggle-status` - Toggle add-on status
- `GET /admin/orders` - Order list
- `GET /admin/orders/{order}` - Order details
- `PATCH /admin/orders/{order}/status` - Update order status
- `PATCH /admin/orders/{order}/payment-status` - Update payment status
- `GET /admin/orders/{order}/print` - Print order receipt

## Customization

### Adding New Products
1. Login as admin
2. Go to Products → Add Product
3. Fill in product details
4. Upload product image (recommended: 600x600px)
5. Select available customization options
6. Save

### Adding New Add-ons
1. Login as admin
2. Go to Add-ons → Add Add-on
3. Fill in add-on name and price
4. Upload add-on image (recommended: 400x400px)
5. Save

### Managing Orders
1. Login as admin
2. Go to Orders
3. View order details
4. Update order status and payment status as needed
5. Print receipts for kitchen or customer

## Troubleshooting

### Common Issues

1. **Storage link not working**
   ```bash
   php artisan storage:link
   ```

2. **Permission issues on Linux/Mac**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

3. **Database connection errors**
   - Check `.env` file for correct database credentials
   - Ensure MySQL is running
   - Verify database exists

4. **404 errors for images**
   - Ensure `storage:link` command was run
   - Check that images are in `storage/app/public/products/`

## Security Considerations

- Change default admin password after first login
- Use strong passwords for all accounts
- Keep Laravel and dependencies updated
- Use HTTPS in production
- Set `APP_DEBUG=false` in production
- Regularly backup database

## License

This project is open-source and available under the MIT License.

## Support

For support or questions, please contact:
- Email: support@milkteashop.com
- Phone: (555) 123-4567

---

**Made with ❤️ for milk tea lovers!**
