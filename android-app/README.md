# Milk Tea Shop Android App

This Android app wraps the Laravel web app in a native shell and supports:

- Online connection to your deployed website
- Login/session persistence (cookies enabled)
- Pull-to-refresh
- File upload from forms (for admin image upload pages)
- Offline state with retry

## 1) Set your online URL

Open `app/build.gradle` and update:

```groovy
buildConfigField "String", "BASE_URL", "\"https://your-domain.com\""
```

Use your real public URL, for example:

```groovy
buildConfigField "String", "BASE_URL", "\"https://milkteashop.example.com\""
```

## 2) Build APK in Android Studio

1. Open Android Studio
2. Select `Open` and choose the `android-app` folder
3. Let Gradle sync
4. Build APK:
   - `Build` > `Build Bundle(s) / APK(s)` > `Build APK(s)`
5. APK output path:
   - `android-app/app/build/outputs/apk/debug/app-debug.apk`

## 3) Install to device

- Enable developer install / unknown sources on your Android phone
- Copy and install `app-debug.apk`

## Notes

- For real phone testing, `127.0.0.1` will not work. Use a public URL.
- For emulator-only local testing, you can use `http://10.0.2.2:8000`.
