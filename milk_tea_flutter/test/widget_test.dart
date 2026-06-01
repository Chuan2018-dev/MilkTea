import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:milk_tea_flutter/main.dart';

void main() {
  testWidgets('customer can log in and view the menu', (tester) async {
    await tester.pumpWidget(const MilkTeaMobileApp());

    expect(find.text('Login'), findsWidgets);
    expect(find.text('Milk Tea Shop'), findsOneWidget);

    await tester.tap(find.byIcon(Icons.visibility_off));
    await tester.pump();
    expect(find.byIcon(Icons.visibility), findsOneWidget);

    await tester.tap(find.widgetWithText(FilledButton, 'Login'));
    await tester.pumpAndSettle();

    expect(find.text('Our Menu'), findsOneWidget);
    expect(find.text('Classic Milk Tea'), findsOneWidget);
  });

  testWidgets('admin catalog is usable on a mobile viewport', (tester) async {
    tester.view.physicalSize = const Size(430, 932);
    tester.view.devicePixelRatio = 1;
    addTearDown(() {
      tester.view.resetPhysicalSize();
      tester.view.resetDevicePixelRatio();
    });

    await tester.pumpWidget(const MilkTeaMobileApp());

    await tester.enterText(
      find.byType(TextFormField).at(0),
      'admin@milktea.test',
    );
    await tester.enterText(find.byType(TextFormField).at(1), 'password123');
    await tester.tap(find.widgetWithText(FilledButton, 'Login'));
    await tester.pumpAndSettle();

    await tester.tap(find.text('Administrator'));
    await tester.pumpAndSettle();
    await tester.tap(find.text('Profile'));
    await tester.pumpAndSettle();

    expect(find.text('Profile'), findsWidgets);
    expect(find.text('Save Profile'), findsOneWidget);

    await tester.pageBack();
    await tester.pumpAndSettle();

    await tester.tap(find.text('Catalog'));
    await tester.pumpAndSettle();

    expect(find.text('Products'), findsWidgets);
    expect(find.text('Classic Milk Tea'), findsOneWidget);

    await tester.tap(find.byTooltip('Edit Classic Milk Tea'));
    await tester.pumpAndSettle();

    expect(find.text('Edit Product'), findsOneWidget);
    expect(find.text('Save Changes'), findsOneWidget);

    await tester.pageBack();
    await tester.pumpAndSettle();

    await tester.tap(find.text('Add-ons'));
    await tester.pumpAndSettle();
    await tester.tap(find.byTooltip('Edit Pearl'));
    await tester.pumpAndSettle();

    expect(find.text('Edit Add-on'), findsOneWidget);

    await tester.pageBack();
    await tester.pumpAndSettle();

    await tester.tap(find.text('Sizes'));
    await tester.pumpAndSettle();
    await tester.tap(find.byTooltip('Edit Small (12oz)'));
    await tester.pumpAndSettle();

    expect(find.text('Edit Size'), findsOneWidget);
  });
}
