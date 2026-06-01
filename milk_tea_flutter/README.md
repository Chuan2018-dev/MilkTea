# Milk Tea Flutter App

Flutter mobile/web version of the Milk Tea Shop ordering system.

## Included Features

- Login and registration with show/hide password controls
- Customer menu with category filters and product-name search
- Product-specific drink artwork generated in Flutter
- Product customization: size, sugar, ice, add-ons, quantity, and notes
- Cart and checkout with the same order computation as the Laravel system
- Customer order list and order details
- Admin dashboard with recent orders
- Admin order status and payment status updates
- Admin catalog views for products, add-ons, and sizes

## Demo Accounts

- Customer: `customer@example.com` / `password`
- Admin: `admin@milktea.test` / `password123`

## Run

```powershell
flutter run
```

## Notes

This version currently runs with local in-app demo data so it can be tested without changing the Laravel database. The next step for production is to add Laravel API endpoints and connect the Flutter app to the live backend.
