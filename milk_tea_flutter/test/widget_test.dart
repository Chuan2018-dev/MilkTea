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
}
