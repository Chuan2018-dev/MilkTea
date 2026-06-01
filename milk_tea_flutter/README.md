# Milk Tea Flutter App

Flutter mobile/web version of the Milk Tea Shop ordering system.

## Included Features

- Login and registration with show/hide password controls
- Customer menu with category filters and product-name search
- Product-specific drink artwork generated in Flutter
- Product customization: size, sugar, ice, add-ons, quantity, and notes
- Cart and checkout with the same order computation as the Laravel system
- Customer order list and order details
- Editable profile details for logged-in users
- Admin dashboard with recent orders
- Admin order status and payment status updates
- Admin catalog views and edit forms for products, add-ons, and sizes
- Optional live sync with the Laravel API every 20 seconds

## Demo Accounts

- Customer: `customer@example.com` / `password`
- Admin: `admin@milktea.test` / `password123`

## Run

```powershell
flutter run
```

Live API mode:

```powershell
flutter run --dart-define=MILK_TEA_LIVE_API=true --dart-define=MILK_TEA_API_URL=https://milkteashop.infinityfreeapp.com/api/mobile
```

## Notes

By default this version runs with local in-app demo data so tests are fast and offline-safe. Release APK builds enable live API sync through GitHub Actions.
