# InfinityFree Deployment Guide

Use this guide for the free online demo of the Milk Tea Ordering System.

## 1. Create Hosting

1. Sign in to InfinityFree.
2. Create a free hosting account and subdomain.
3. Open the account control panel.

## 2. Create MySQL Database

1. Go to **MySQL Databases**.
2. Create one database.
3. Copy these values because they are needed in `.env`:
   - MySQL host
   - Database name
   - Database username
   - Database password

## 3. Import Database

1. Open **phpMyAdmin** from the InfinityFree control panel.
2. Select the database you created.
3. Use **Import**.
4. Upload this file:

```text
database/deployment/milktea_infinityfree.sql
```

## 4. Upload Files

Use the prepared ZIP package:

```text
deployment-output/MilkTea-InfinityFree.zip
```

The ZIP contains:

```text
app_core/   Laravel app files, vendor files, storage, routes, config, views
htdocs/     Public web files for InfinityFree
```

Upload both folders to the hosting account root so they are siblings:

```text
app_core/
htdocs/
```

If the file manager opens directly inside `htdocs`, use an FTP client so you can upload `app_core` beside `htdocs`.

## 5. Edit app_core/.env

Inside `app_core/.env`, update:

```env
APP_URL=https://your-subdomain.infinityfreeapp.com

DB_HOST=your_mysql_host
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Keep this for shared hosting image uploads:

```env
FILESYSTEM_DISK=public
PUBLIC_STORAGE_PATH=../htdocs/storage
```

## 6. Login Accounts

Admin:

```text
Email: admin@milktea.test
Password: password123
```

Customer:

```text
Email: customer@example.com
Password: password
```

## 7. Quick Checks

Open the live URL and test:

- Login as admin
- Create/edit/delete a product
- Login as customer
- Add product to cart with size, sugar, ice, and add-ons
- Checkout
- Update order status in admin
