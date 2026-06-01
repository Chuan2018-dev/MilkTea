import 'package:flutter/material.dart';

void main() {
  runApp(const MilkTeaMobileApp());
}

const _brand = Color(0xFF8B5A2B);
const _brandDark = Color(0xFF5B3218);
const _cream = Color(0xFFFFF7ED);
const _surface = Color(0xFFF8F5F0);
const _success = Color(0xFF1F8F55);
const _warning = Color(0xFFFFC107);

class MilkTeaMobileApp extends StatefulWidget {
  const MilkTeaMobileApp({super.key});

  @override
  State<MilkTeaMobileApp> createState() => _MilkTeaMobileAppState();
}

class _MilkTeaMobileAppState extends State<MilkTeaMobileApp> {
  final AppState appState = AppState.seeded();

  @override
  Widget build(BuildContext context) {
    return AppScope(
      notifier: appState,
      child: MaterialApp(
        debugShowCheckedModeBanner: false,
        title: 'Milk Tea Shop',
        theme: ThemeData(
          useMaterial3: true,
          colorScheme: ColorScheme.fromSeed(
            seedColor: _brand,
            brightness: Brightness.light,
            surface: _surface,
          ),
          scaffoldBackgroundColor: _surface,
          appBarTheme: const AppBarTheme(
            centerTitle: false,
            backgroundColor: Colors.white,
            foregroundColor: Color(0xFF2F2924),
            elevation: 0,
            surfaceTintColor: Colors.white,
          ),
          cardTheme: CardThemeData(
            color: Colors.white,
            elevation: 1.5,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8),
            ),
          ),
          filledButtonTheme: FilledButtonThemeData(
            style: FilledButton.styleFrom(
              backgroundColor: _brand,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(8),
              ),
            ),
          ),
          inputDecorationTheme: InputDecorationTheme(
            filled: true,
            fillColor: Colors.white,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
          ),
        ),
        home: const AuthGate(),
      ),
    );
  }
}

class AppScope extends InheritedNotifier<AppState> {
  const AppScope({required super.notifier, required super.child, super.key});

  static AppState of(BuildContext context) {
    final scope = context.dependOnInheritedWidgetOfExactType<AppScope>();
    assert(scope != null, 'AppScope not found');
    return scope!.notifier!;
  }
}

class AuthGate extends StatelessWidget {
  const AuthGate({super.key});

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    final user = state.currentUser;

    if (user == null) {
      return const LoginScreen();
    }

    if (user.role == UserRole.admin) {
      return const AdminHome();
    }

    return const CustomerHome();
  }
}

enum UserRole { customer, admin }

class AppUser {
  const AppUser({
    required this.name,
    required this.email,
    required this.password,
    required this.role,
    this.phone = '',
    this.address = '',
  });

  final String name;
  final String email;
  final String password;
  final UserRole role;
  final String phone;
  final String address;

  AppUser copyWith({
    String? name,
    String? password,
    String? phone,
    String? address,
  }) {
    return AppUser(
      name: name ?? this.name,
      email: email,
      password: password ?? this.password,
      role: role,
      phone: phone ?? this.phone,
      address: address ?? this.address,
    );
  }
}

class Product {
  const Product({
    required this.id,
    required this.name,
    required this.description,
    required this.basePrice,
    required this.category,
    required this.primary,
    required this.secondary,
  });

  final int id;
  final String name;
  final String description;
  final double basePrice;
  final String category;
  final Color primary;
  final Color secondary;

  String get formattedPrice => peso(basePrice);

  Product copyWith({
    String? name,
    String? description,
    double? basePrice,
    String? category,
  }) {
    return Product(
      id: id,
      name: name ?? this.name,
      description: description ?? this.description,
      basePrice: basePrice ?? this.basePrice,
      category: category ?? this.category,
      primary: primary,
      secondary: secondary,
    );
  }
}

class SizeOption {
  const SizeOption({
    required this.name,
    required this.displayName,
    required this.adjustment,
  });

  final String name;
  final String displayName;
  final double adjustment;

  SizeOption copyWith({String? displayName, double? adjustment}) {
    return SizeOption(
      name: name,
      displayName: displayName ?? this.displayName,
      adjustment: adjustment ?? this.adjustment,
    );
  }
}

class AddOnOption {
  const AddOnOption({
    required this.name,
    required this.description,
    required this.price,
  });

  final String name;
  final String description;
  final double price;

  AddOnOption copyWith({String? name, String? description, double? price}) {
    return AddOnOption(
      name: name ?? this.name,
      description: description ?? this.description,
      price: price ?? this.price,
    );
  }
}

class CartItem {
  const CartItem({
    required this.product,
    required this.size,
    required this.sugarLevel,
    required this.iceLevel,
    required this.addOns,
    required this.quantity,
    this.notes = '',
  });

  final Product product;
  final SizeOption size;
  final String sugarLevel;
  final String iceLevel;
  final List<AddOnOption> addOns;
  final int quantity;
  final String notes;

  double get unitPrice {
    return product.basePrice +
        size.adjustment +
        addOns.fold<double>(0, (total, addOn) => total + addOn.price);
  }

  double get total => unitPrice * quantity;

  CartItem copyWith({int? quantity}) {
    return CartItem(
      product: product,
      size: size,
      sugarLevel: sugarLevel,
      iceLevel: iceLevel,
      addOns: addOns,
      quantity: quantity ?? this.quantity,
      notes: notes,
    );
  }
}

class Order {
  const Order({
    required this.number,
    required this.userEmail,
    required this.customerName,
    required this.contactNumber,
    required this.address,
    required this.paymentMethod,
    required this.pickupMethod,
    required this.status,
    required this.paymentStatus,
    required this.items,
    required this.createdAt,
    this.notes = '',
  });

  final String number;
  final String userEmail;
  final String customerName;
  final String contactNumber;
  final String address;
  final String paymentMethod;
  final String pickupMethod;
  final String status;
  final String paymentStatus;
  final List<CartItem> items;
  final DateTime createdAt;
  final String notes;

  double get subtotal => items.fold(0, (total, item) => total + item.total);
  double get tax => subtotal * 0.08;
  double get total => subtotal + tax;

  Order copyWith({String? status, String? paymentStatus}) {
    return Order(
      number: number,
      userEmail: userEmail,
      customerName: customerName,
      contactNumber: contactNumber,
      address: address,
      paymentMethod: paymentMethod,
      pickupMethod: pickupMethod,
      status: status ?? this.status,
      paymentStatus: paymentStatus ?? this.paymentStatus,
      items: items,
      createdAt: createdAt,
      notes: notes,
    );
  }
}

class AppState extends ChangeNotifier {
  AppState.seeded()
    : users = [
        const AppUser(
          name: 'Administrator',
          email: 'admin@milktea.test',
          password: 'password123',
          role: UserRole.admin,
          phone: '09123456789',
          address: '123 Admin Street, Manila, Philippines',
        ),
        const AppUser(
          name: 'John Doe',
          email: 'customer@example.com',
          password: 'password',
          role: UserRole.customer,
          phone: '09987654321',
          address: '456 Customer Ave, Quezon City, Philippines',
        ),
      ];

  final List<AppUser> users;
  AppUser? currentUser;
  final List<CartItem> cart = [];
  final List<Order> orders = [];

  bool login(String email, String password) {
    final normalized = email.trim().toLowerCase();
    for (final user in users) {
      if (user.email.toLowerCase() == normalized && user.password == password) {
        currentUser = user;
        notifyListeners();
        return true;
      }
    }
    return false;
  }

  bool register({
    required String name,
    required String email,
    required String password,
    required String phone,
  }) {
    final normalized = email.trim().toLowerCase();
    final exists = users.any((user) => user.email.toLowerCase() == normalized);
    if (exists) {
      return false;
    }

    final user = AppUser(
      name: name.trim(),
      email: normalized,
      password: password,
      role: UserRole.customer,
      phone: phone.trim(),
    );
    users.add(user);
    currentUser = user;
    notifyListeners();
    return true;
  }

  void logout() {
    currentUser = null;
    cart.clear();
    notifyListeners();
  }

  void updateCurrentUser({
    required String name,
    required String phone,
    required String address,
    String? password,
  }) {
    final user = currentUser;
    if (user == null) {
      return;
    }

    final updated = user.copyWith(
      name: name.trim(),
      phone: phone.trim(),
      address: address.trim(),
      password: password == null || password.trim().isEmpty
          ? user.password
          : password,
    );
    final index = users.indexWhere((item) => item.email == user.email);
    if (index != -1) {
      users[index] = updated;
    }
    currentUser = updated;
    notifyListeners();
  }

  void addToCart(CartItem item) {
    cart.add(item);
    notifyListeners();
  }

  void updateCartQuantity(int index, int quantity) {
    if (index < 0 || index >= cart.length) {
      return;
    }
    cart[index] = cart[index].copyWith(quantity: quantity.clamp(1, 50));
    notifyListeners();
  }

  void removeCartItem(int index) {
    if (index < 0 || index >= cart.length) {
      return;
    }
    cart.removeAt(index);
    notifyListeners();
  }

  void clearCart() {
    cart.clear();
    notifyListeners();
  }

  Order placeOrder({
    required String customerName,
    required String contactNumber,
    required String address,
    required String paymentMethod,
    required String pickupMethod,
    required String notes,
  }) {
    final now = DateTime.now();
    final order = Order(
      number:
          'MT-${now.year}${two(now.month)}${two(now.day)}-${orders.length + 1}F',
      userEmail: currentUser!.email,
      customerName: customerName,
      contactNumber: contactNumber,
      address: address,
      paymentMethod: paymentMethod,
      pickupMethod: pickupMethod,
      status: 'pending',
      paymentStatus: 'pending',
      items: List<CartItem>.from(cart),
      createdAt: now,
      notes: notes,
    );
    orders.insert(0, order);
    cart.clear();
    notifyListeners();
    return order;
  }

  void updateOrderStatus(String orderNumber, String status) {
    final index = orders.indexWhere((order) => order.number == orderNumber);
    if (index == -1) {
      return;
    }
    orders[index] = orders[index].copyWith(status: status);
    notifyListeners();
  }

  void updatePaymentStatus(String orderNumber, String status) {
    final index = orders.indexWhere((order) => order.number == orderNumber);
    if (index == -1) {
      return;
    }
    orders[index] = orders[index].copyWith(paymentStatus: status);
    notifyListeners();
  }

  double get cartSubtotal => cart.fold(0, (total, item) => total + item.total);
  double get cartTax => cartSubtotal * 0.08;
  double get cartTotal => cartSubtotal + cartTax;

  List<Order> get currentUserOrders {
    final user = currentUser;
    if (user == null) {
      return [];
    }
    return orders.where((order) => order.userEmail == user.email).toList();
  }

  void updateProduct(Product updatedProduct) {
    final index = products.indexWhere(
      (product) => product.id == updatedProduct.id,
    );
    if (index == -1) {
      return;
    }
    products[index] = updatedProduct;
    notifyListeners();
  }

  void updateAddOn(AddOnOption currentAddOn, AddOnOption updatedAddOn) {
    final index = addOns.indexOf(currentAddOn);
    if (index == -1) {
      return;
    }
    addOns[index] = updatedAddOn;
    notifyListeners();
  }

  void updateSize(SizeOption currentSize, SizeOption updatedSize) {
    final index = sizes.indexOf(currentSize);
    if (index == -1) {
      return;
    }
    sizes[index] = updatedSize;
    notifyListeners();
  }
}

final products = <Product>[
  const Product(
    id: 1,
    name: 'Classic Milk Tea',
    description: 'Our signature black milk tea with a rich, creamy flavor.',
    basePrice: 80,
    category: 'milk_tea',
    primary: Color(0xFFD0A16F),
    secondary: Color(0xFF7B431E),
  ),
  const Product(
    id: 2,
    name: 'Wintermelon Milk Tea',
    description: 'Refreshing wintermelon flavor blended with creamy milk.',
    basePrice: 85,
    category: 'milk_tea',
    primary: Color(0xFFD9EDB4),
    secondary: Color(0xFF75A843),
  ),
  const Product(
    id: 3,
    name: 'Okinawa Milk Tea',
    description: 'Brown sugar flavored milk tea with a caramel taste.',
    basePrice: 90,
    category: 'milk_tea',
    primary: Color(0xFFE8A05B),
    secondary: Color(0xFF82441F),
  ),
  const Product(
    id: 4,
    name: 'Thai Milk Tea',
    description: 'Authentic Thai tea with a unique orange color and flavor.',
    basePrice: 95,
    category: 'milk_tea',
    primary: Color(0xFFFFB05C),
    secondary: Color(0xFFE76A15),
  ),
  const Product(
    id: 5,
    name: 'Taro Milk Tea',
    description: 'Creamy taro flavor with a beautiful purple color.',
    basePrice: 90,
    category: 'milk_tea',
    primary: Color(0xFFD6B7EF),
    secondary: Color(0xFF7A4AB0),
  ),
  const Product(
    id: 6,
    name: 'Matcha Milk Tea',
    description: 'Premium Japanese green tea with milk.',
    basePrice: 100,
    category: 'milk_tea',
    primary: Color(0xFFBEE4AC),
    secondary: Color(0xFF4E8A39),
  ),
  const Product(
    id: 7,
    name: 'Strawberry Fruit Tea',
    description: 'Fresh strawberry flavor with green tea base.',
    basePrice: 85,
    category: 'fruit_tea',
    primary: Color(0xFFF7A6B9),
    secondary: Color(0xFFE83F69),
  ),
  const Product(
    id: 8,
    name: 'Mango Fruit Tea',
    description: 'Tropical mango flavor with green tea base.',
    basePrice: 85,
    category: 'fruit_tea',
    primary: Color(0xFFFFDB75),
    secondary: Color(0xFFEAA21B),
  ),
  const Product(
    id: 9,
    name: 'Passion Fruit Tea',
    description: 'Refreshing passion fruit with green tea.',
    basePrice: 80,
    category: 'fruit_tea',
    primary: Color(0xFFF9DB63),
    secondary: Color(0xFFC38411),
  ),
  const Product(
    id: 10,
    name: 'Iced Americano',
    description: 'Strong espresso with cold water and ice.',
    basePrice: 75,
    category: 'coffee',
    primary: Color(0xFFB7AEA6),
    secondary: Color(0xFF2D2019),
  ),
  const Product(
    id: 11,
    name: 'Caramel Macchiato',
    description:
        'Espresso with vanilla syrup, steamed milk, and caramel drizzle.',
    basePrice: 110,
    category: 'coffee',
    primary: Color(0xFFE0A45C),
    secondary: Color(0xFF8E5428),
  ),
  const Product(
    id: 12,
    name: 'Hazelnut Latte',
    description: 'Smooth latte with hazelnut flavor.',
    basePrice: 105,
    category: 'coffee',
    primary: Color(0xFFC5A17E),
    secondary: Color(0xFF714621),
  ),
];

final sizes = <SizeOption>[
  const SizeOption(name: 'small', displayName: 'Small (12oz)', adjustment: 0),
  const SizeOption(
    name: 'medium',
    displayName: 'Medium (16oz)',
    adjustment: 10,
  ),
  const SizeOption(name: 'large', displayName: 'Large (22oz)', adjustment: 20),
];

final addOns = <AddOnOption>[
  const AddOnOption(
    name: 'Pearl',
    description: 'Chewy tapioca pearls',
    price: 10,
  ),
  const AddOnOption(
    name: 'Nata',
    description: 'Sweet coconut jelly',
    price: 10,
  ),
  const AddOnOption(
    name: 'Pudding',
    description: 'Creamy egg pudding',
    price: 15,
  ),
  const AddOnOption(
    name: 'Grass Jelly',
    description: 'Refreshing herbal jelly',
    price: 10,
  ),
  const AddOnOption(
    name: 'Aloe Vera',
    description: 'Healthy aloe vera pieces',
    price: 15,
  ),
  const AddOnOption(
    name: 'Red Bean',
    description: 'Sweet red beans',
    price: 15,
  ),
];

const sugarLevels = ['0%', '25%', '50%', '75%', '100%'];
const iceLevels = ['no_ice', 'less_ice', 'normal_ice'];
const orderStatuses = [
  'pending',
  'confirmed',
  'preparing',
  'ready',
  'completed',
  'cancelled',
];
const paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final formKey = GlobalKey<FormState>();
  final email = TextEditingController(text: 'customer@example.com');
  final password = TextEditingController(text: 'password');
  bool showPassword = false;

  @override
  void dispose() {
    email.dispose();
    password.dispose();
    super.dispose();
  }

  void submit() {
    if (!formKey.currentState!.validate()) {
      return;
    }
    final ok = AppScope.of(context).login(email.text, password.text);
    if (!ok) {
      showSnack(context, 'Invalid email or password.');
    }
  }

  @override
  Widget build(BuildContext context) {
    return AuthScaffold(
      title: 'Login',
      icon: Icons.login,
      child: Form(
        key: formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            TextFormField(
              controller: email,
              keyboardType: TextInputType.emailAddress,
              decoration: const InputDecoration(
                labelText: 'Email Address',
                prefixIcon: Icon(Icons.mail_outline),
              ),
              validator: requiredField,
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: password,
              obscureText: !showPassword,
              decoration: InputDecoration(
                labelText: 'Password',
                prefixIcon: const Icon(Icons.lock_outline),
                suffixIcon: IconButton(
                  tooltip: showPassword ? 'Hide password' : 'Show password',
                  icon: Icon(
                    showPassword ? Icons.visibility : Icons.visibility_off,
                  ),
                  onPressed: () => setState(() => showPassword = !showPassword),
                ),
              ),
              validator: requiredField,
              onFieldSubmitted: (_) => submit(),
            ),
            const SizedBox(height: 18),
            FilledButton.icon(
              onPressed: submit,
              icon: const Icon(Icons.login),
              label: const Text('Login'),
            ),
            const SizedBox(height: 14),
            OutlinedButton.icon(
              onPressed: () {
                Navigator.of(context).push(
                  MaterialPageRoute(builder: (_) => const RegisterScreen()),
                );
              },
              icon: const Icon(Icons.person_add_alt_1),
              label: const Text('Create Account'),
            ),
            const SizedBox(height: 18),
            const Text(
              'Demo accounts: customer@example.com / password or admin@milktea.test / password123',
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.black54),
            ),
          ],
        ),
      ),
    );
  }
}

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final formKey = GlobalKey<FormState>();
  final name = TextEditingController();
  final email = TextEditingController();
  final phone = TextEditingController();
  final password = TextEditingController();
  final confirmPassword = TextEditingController();
  bool showPassword = false;
  bool showConfirmPassword = false;

  @override
  void dispose() {
    name.dispose();
    email.dispose();
    phone.dispose();
    password.dispose();
    confirmPassword.dispose();
    super.dispose();
  }

  void submit() {
    if (!formKey.currentState!.validate()) {
      return;
    }
    final ok = AppScope.of(context).register(
      name: name.text,
      email: email.text,
      password: password.text,
      phone: phone.text,
    );
    if (!ok) {
      showSnack(context, 'Email already exists.');
      return;
    }
    Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return AuthScaffold(
      title: 'Register',
      icon: Icons.person_add_alt_1,
      child: Form(
        key: formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            TextFormField(
              controller: name,
              decoration: const InputDecoration(
                labelText: 'Full Name',
                prefixIcon: Icon(Icons.person_outline),
              ),
              validator: requiredField,
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: email,
              keyboardType: TextInputType.emailAddress,
              decoration: const InputDecoration(
                labelText: 'Email Address',
                prefixIcon: Icon(Icons.mail_outline),
              ),
              validator: (value) {
                if (value == null || value.trim().isEmpty) {
                  return 'Required';
                }
                return value.contains('@') ? null : 'Enter a valid email';
              },
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: phone,
              keyboardType: TextInputType.phone,
              decoration: const InputDecoration(
                labelText: 'Phone Number',
                prefixIcon: Icon(Icons.phone_outlined),
              ),
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: password,
              obscureText: !showPassword,
              decoration: InputDecoration(
                labelText: 'Password',
                prefixIcon: const Icon(Icons.lock_outline),
                suffixIcon: IconButton(
                  tooltip: showPassword ? 'Hide password' : 'Show password',
                  icon: Icon(
                    showPassword ? Icons.visibility : Icons.visibility_off,
                  ),
                  onPressed: () => setState(() => showPassword = !showPassword),
                ),
              ),
              validator: (value) {
                if (value == null || value.length < 8) {
                  return 'Minimum 8 characters';
                }
                return null;
              },
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: confirmPassword,
              obscureText: !showConfirmPassword,
              decoration: InputDecoration(
                labelText: 'Confirm Password',
                prefixIcon: const Icon(Icons.lock_reset),
                suffixIcon: IconButton(
                  tooltip: showConfirmPassword
                      ? 'Hide password'
                      : 'Show password',
                  icon: Icon(
                    showConfirmPassword
                        ? Icons.visibility
                        : Icons.visibility_off,
                  ),
                  onPressed: () => setState(
                    () => showConfirmPassword = !showConfirmPassword,
                  ),
                ),
              ),
              validator: (value) {
                if (value != password.text) {
                  return 'Passwords do not match';
                }
                return null;
              },
            ),
            const SizedBox(height: 18),
            FilledButton.icon(
              onPressed: submit,
              icon: const Icon(Icons.person_add_alt_1),
              label: const Text('Register'),
            ),
          ],
        ),
      ),
    );
  }
}

class AuthScaffold extends StatelessWidget {
  const AuthScaffold({
    required this.title,
    required this.icon,
    required this.child,
    super.key,
  });

  final String title;
  final IconData icon;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(20),
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 430),
              child: Column(
                children: [
                  const BrandHeader(),
                  const SizedBox(height: 24),
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(20),
                      child: Column(
                        children: [
                          Icon(icon, color: _brand, size: 34),
                          const SizedBox(height: 8),
                          Text(
                            title,
                            style: Theme.of(context).textTheme.headlineSmall
                                ?.copyWith(fontWeight: FontWeight.w800),
                          ),
                          const SizedBox(height: 20),
                          child,
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final formKey = GlobalKey<FormState>();
  late final TextEditingController name;
  late final TextEditingController phone;
  late final TextEditingController address;
  final password = TextEditingController();
  final confirmPassword = TextEditingController();
  bool showPassword = false;
  bool showConfirmPassword = false;
  bool initialized = false;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (initialized) {
      return;
    }
    final user = AppScope.of(context).currentUser!;
    name = TextEditingController(text: user.name);
    phone = TextEditingController(text: user.phone);
    address = TextEditingController(text: user.address);
    initialized = true;
  }

  @override
  void dispose() {
    name.dispose();
    phone.dispose();
    address.dispose();
    password.dispose();
    confirmPassword.dispose();
    super.dispose();
  }

  void submit() {
    if (!formKey.currentState!.validate()) {
      return;
    }

    AppScope.of(context).updateCurrentUser(
      name: name.text,
      phone: phone.text,
      address: address.text,
      password: password.text.trim().isEmpty ? null : password.text,
    );
    Navigator.of(context).pop();
    showSnack(context, 'Profile updated.');
  }

  @override
  Widget build(BuildContext context) {
    final user = AppScope.of(context).currentUser!;
    return Scaffold(
      appBar: const MilkTeaAppBar(title: 'Profile'),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          SectionCard(
            title: 'Account Information',
            icon: Icons.person_outline,
            child: Form(
              key: formKey,
              child: Column(
                children: [
                  TextFormField(
                    controller: name,
                    decoration: const InputDecoration(labelText: 'Full Name'),
                    validator: requiredField,
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    initialValue: user.email,
                    readOnly: true,
                    decoration: const InputDecoration(
                      labelText: 'Email Address',
                      helperText: 'Email is used as the login account.',
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    controller: phone,
                    keyboardType: TextInputType.phone,
                    decoration: const InputDecoration(
                      labelText: 'Phone Number',
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    controller: address,
                    minLines: 2,
                    maxLines: 3,
                    decoration: const InputDecoration(labelText: 'Address'),
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    controller: password,
                    obscureText: !showPassword,
                    decoration: InputDecoration(
                      labelText: 'New Password',
                      helperText: 'Leave blank to keep current password.',
                      suffixIcon: IconButton(
                        tooltip: showPassword
                            ? 'Hide password'
                            : 'Show password',
                        icon: Icon(
                          showPassword
                              ? Icons.visibility
                              : Icons.visibility_off,
                        ),
                        onPressed: () =>
                            setState(() => showPassword = !showPassword),
                      ),
                    ),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return null;
                      }
                      return value.length >= 8 ? null : 'Minimum 8 characters';
                    },
                  ),
                  const SizedBox(height: 12),
                  TextFormField(
                    controller: confirmPassword,
                    obscureText: !showConfirmPassword,
                    decoration: InputDecoration(
                      labelText: 'Confirm New Password',
                      suffixIcon: IconButton(
                        tooltip: showConfirmPassword
                            ? 'Hide password'
                            : 'Show password',
                        icon: Icon(
                          showConfirmPassword
                              ? Icons.visibility
                              : Icons.visibility_off,
                        ),
                        onPressed: () => setState(
                          () => showConfirmPassword = !showConfirmPassword,
                        ),
                      ),
                    ),
                    validator: (value) {
                      if (password.text.trim().isEmpty) {
                        return null;
                      }
                      return value == password.text
                          ? null
                          : 'Passwords do not match';
                    },
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: FilledButton.icon(
                      onPressed: submit,
                      icon: const Icon(Icons.save_outlined),
                      label: const Text('Save Profile'),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class CustomerHome extends StatefulWidget {
  const CustomerHome({super.key});

  @override
  State<CustomerHome> createState() => _CustomerHomeState();
}

class _CustomerHomeState extends State<CustomerHome> {
  int index = 0;

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    final pages = [
      const MenuScreen(),
      const CartScreen(),
      const CustomerOrdersScreen(),
    ];

    return Scaffold(
      appBar: MilkTeaAppBar(
        title: 'Milk Tea Shop',
        actions: [
          Badge(
            label: Text('${state.cart.length}'),
            isLabelVisible: state.cart.isNotEmpty,
            child: IconButton(
              tooltip: 'Cart',
              icon: const Icon(Icons.shopping_cart_outlined),
              onPressed: () => setState(() => index = 1),
            ),
          ),
          UserMenu(name: state.currentUser?.name ?? '', onLogout: state.logout),
        ],
      ),
      body: IndexedStack(index: index, children: pages),
      bottomNavigationBar: NavigationBar(
        selectedIndex: index,
        onDestinationSelected: (value) => setState(() => index = value),
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.local_cafe_outlined),
            label: 'Menu',
          ),
          NavigationDestination(
            icon: Icon(Icons.shopping_cart_outlined),
            label: 'Cart',
          ),
          NavigationDestination(
            icon: Icon(Icons.receipt_long_outlined),
            label: 'Orders',
          ),
        ],
      ),
    );
  }
}

class AdminHome extends StatefulWidget {
  const AdminHome({super.key});

  @override
  State<AdminHome> createState() => _AdminHomeState();
}

class _AdminHomeState extends State<AdminHome> {
  int index = 0;

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    return Scaffold(
      appBar: MilkTeaAppBar(
        title: 'Admin Panel',
        actions: [
          UserMenu(name: state.currentUser?.name ?? '', onLogout: state.logout),
        ],
      ),
      body: IndexedStack(
        index: index,
        children: const [
          AdminDashboardScreen(),
          AdminOrdersScreen(),
          AdminCatalogScreen(),
        ],
      ),
      bottomNavigationBar: NavigationBar(
        selectedIndex: index,
        onDestinationSelected: (value) => setState(() => index = value),
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.speed_outlined),
            label: 'Dashboard',
          ),
          NavigationDestination(
            icon: Icon(Icons.receipt_long_outlined),
            label: 'Orders',
          ),
          NavigationDestination(
            icon: Icon(Icons.inventory_2_outlined),
            label: 'Catalog',
          ),
        ],
      ),
    );
  }
}

class MenuScreen extends StatefulWidget {
  const MenuScreen({super.key});

  @override
  State<MenuScreen> createState() => _MenuScreenState();
}

class _MenuScreenState extends State<MenuScreen> {
  String category = 'all';
  String search = '';

  List<Product> get filteredProducts {
    final query = search.trim().toLowerCase();
    return products.where((product) {
      final categoryOk = category == 'all' || product.category == category;
      final searchOk =
          query.isEmpty || product.name.toLowerCase().contains(query);
      return categoryOk && searchOk;
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    final items = filteredProducts;
    return ListView(
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 28),
      children: [
        const MenuHero(),
        const SizedBox(height: 16),
        CategoryFilter(
          selected: category,
          onChanged: (value) => setState(() => category = value),
        ),
        const SizedBox(height: 12),
        TextField(
          decoration: const InputDecoration(
            hintText: 'Search products...',
            prefixIcon: Icon(Icons.search),
          ),
          onChanged: (value) => setState(() => search = value),
        ),
        if (search.trim().isNotEmpty) ...[
          const SizedBox(height: 12),
          InfoPill(text: 'Showing results for "$search"'),
        ],
        const SizedBox(height: 16),
        if (items.isEmpty)
          const EmptyState(
            icon: Icons.search_off,
            title: 'No products found',
            message: 'Try another product name or clear the search.',
          )
        else
          LayoutBuilder(
            builder: (context, constraints) {
              final columns = constraints.maxWidth >= 900
                  ? 4
                  : constraints.maxWidth >= 620
                  ? 3
                  : constraints.maxWidth >= 430
                  ? 2
                  : 1;
              return GridView.builder(
                itemCount: items.length,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: columns,
                  mainAxisSpacing: 14,
                  crossAxisSpacing: 14,
                  childAspectRatio: columns == 1 ? 0.84 : 0.58,
                ),
                itemBuilder: (context, index) =>
                    ProductCard(product: items[index]),
              );
            },
          ),
      ],
    );
  }
}

class ProductCard extends StatelessWidget {
  const ProductCard({required this.product, super.key});

  final Product product;

  @override
  Widget build(BuildContext context) {
    return Card(
      clipBehavior: Clip.antiAlias,
      child: InkWell(
        onTap: () => openProduct(context, product),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ProductArt(product: product, height: 150),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Expanded(
                          child: Text(
                            product.name,
                            style: Theme.of(context).textTheme.titleMedium
                                ?.copyWith(fontWeight: FontWeight.w800),
                          ),
                        ),
                        PriceBadge(text: product.formattedPrice),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Expanded(
                      child: Text(
                        product.description,
                        maxLines: 3,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(color: Colors.black54),
                      ),
                    ),
                    const SizedBox(height: 12),
                    SizedBox(
                      width: double.infinity,
                      child: FilledButton.icon(
                        onPressed: () => openProduct(context, product),
                        icon: const Icon(Icons.add),
                        label: const Text('Customize & Add'),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class ProductDetailScreen extends StatefulWidget {
  const ProductDetailScreen({required this.product, super.key});

  final Product product;

  @override
  State<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends State<ProductDetailScreen> {
  SizeOption selectedSize = sizes.first;
  String sugar = '50%';
  String ice = 'normal_ice';
  final selectedAddOns = <AddOnOption>{};
  int quantity = 1;
  final notes = TextEditingController();

  @override
  void dispose() {
    notes.dispose();
    super.dispose();
  }

  double get unitPrice {
    return widget.product.basePrice +
        selectedSize.adjustment +
        selectedAddOns.fold<double>(0, (total, addOn) => total + addOn.price);
  }

  void addItem() {
    AppScope.of(context).addToCart(
      CartItem(
        product: widget.product,
        size: selectedSize,
        sugarLevel: sugar,
        iceLevel: ice,
        addOns: selectedAddOns.toList(),
        quantity: quantity,
        notes: notes.text,
      ),
    );
    Navigator.of(context).pop();
    showSnack(context, '${widget.product.name} added to cart.');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: MilkTeaAppBar(title: widget.product.name),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          ProductArt(product: widget.product, height: 220),
          const SizedBox(height: 16),
          Text(
            widget.product.name,
            style: Theme.of(
              context,
            ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w900),
          ),
          const SizedBox(height: 6),
          Text(
            widget.product.description,
            style: const TextStyle(color: Colors.black54),
          ),
          const SizedBox(height: 18),
          SectionCard(
            title: 'Size',
            icon: Icons.straighten,
            child: Wrap(
              spacing: 8,
              runSpacing: 8,
              children: sizes
                  .map(
                    (size) => ChoiceChip(
                      selected: selectedSize == size,
                      label: Text(
                        size.adjustment == 0
                            ? '${size.displayName} | Base'
                            : '${size.displayName} | +${peso(size.adjustment)}',
                      ),
                      onSelected: (_) => setState(() => selectedSize = size),
                    ),
                  )
                  .toList(),
            ),
          ),
          SectionCard(
            title: 'Sugar Level',
            icon: Icons.water_drop_outlined,
            child: Wrap(
              spacing: 8,
              runSpacing: 8,
              children: sugarLevels
                  .map(
                    (level) => ChoiceChip(
                      selected: sugar == level,
                      label: Text(level),
                      onSelected: (_) => setState(() => sugar = level),
                    ),
                  )
                  .toList(),
            ),
          ),
          SectionCard(
            title: 'Ice Level',
            icon: Icons.ac_unit,
            child: Wrap(
              spacing: 8,
              runSpacing: 8,
              children: iceLevels
                  .map(
                    (level) => ChoiceChip(
                      selected: ice == level,
                      label: Text(labelize(level)),
                      onSelected: (_) => setState(() => ice = level),
                    ),
                  )
                  .toList(),
            ),
          ),
          SectionCard(
            title: 'Add-ons',
            icon: Icons.add_circle_outline,
            child: Column(
              children: addOns
                  .map(
                    (addOn) => CheckboxListTile(
                      value: selectedAddOns.contains(addOn),
                      contentPadding: EdgeInsets.zero,
                      title: Text(addOn.name),
                      subtitle: Text(addOn.description),
                      secondary: PriceBadge(text: peso(addOn.price)),
                      onChanged: (checked) {
                        setState(() {
                          if (checked ?? false) {
                            selectedAddOns.add(addOn);
                          } else {
                            selectedAddOns.remove(addOn);
                          }
                        });
                      },
                    ),
                  )
                  .toList(),
            ),
          ),
          SectionCard(
            title: 'Quantity & Notes',
            icon: Icons.tune,
            child: Column(
              children: [
                QuantityStepper(
                  value: quantity,
                  onChanged: (value) => setState(() => quantity = value),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: notes,
                  maxLines: 2,
                  decoration: const InputDecoration(
                    labelText: 'Special Instructions',
                    hintText: 'Any special request...',
                  ),
                ),
              ],
            ),
          ),
          SummaryBar(
            label: 'Item Total',
            value: peso(unitPrice * quantity),
            action: FilledButton.icon(
              onPressed: addItem,
              icon: const Icon(Icons.shopping_cart_outlined),
              label: const Text('Add to Cart'),
            ),
          ),
        ],
      ),
    );
  }
}

class CartScreen extends StatelessWidget {
  const CartScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    if (state.cart.isEmpty) {
      return const EmptyState(
        icon: Icons.shopping_cart_outlined,
        title: 'Your cart is empty',
        message: 'Pick a drink from the menu to start an order.',
      );
    }

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Text(
          'Shopping Cart',
          style: Theme.of(
            context,
          ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w900),
        ),
        const SizedBox(height: 12),
        ...state.cart.indexed.map(
          (entry) => CartItemTile(index: entry.$1, item: entry.$2),
        ),
        const SizedBox(height: 10),
        OrderTotals(
          subtotal: state.cartSubtotal,
          tax: state.cartTax,
          total: state.cartTotal,
        ),
        const SizedBox(height: 14),
        FilledButton.icon(
          onPressed: () {
            Navigator.of(
              context,
            ).push(MaterialPageRoute(builder: (_) => const CheckoutScreen()));
          },
          icon: const Icon(Icons.credit_card),
          label: const Text('Proceed to Checkout'),
        ),
        TextButton.icon(
          onPressed: state.clearCart,
          icon: const Icon(Icons.delete_outline),
          label: const Text('Clear Cart'),
        ),
      ],
    );
  }
}

class CartItemTile extends StatelessWidget {
  const CartItemTile({required this.index, required this.item, super.key});

  final int index;
  final CartItem item;

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: SizedBox(
                width: 82,
                height: 82,
                child: ProductArt(
                  product: item.product,
                  height: 82,
                  compact: true,
                ),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    item.product.name,
                    style: const TextStyle(fontWeight: FontWeight.w800),
                  ),
                  const SizedBox(height: 3),
                  Text(
                    '${item.size.displayName} | Sugar ${item.sugarLevel} | ${labelize(item.iceLevel)}',
                    style: const TextStyle(color: Colors.black54, fontSize: 12),
                  ),
                  if (item.addOns.isNotEmpty)
                    Text(
                      'Add-ons: ${item.addOns.map((addOn) => addOn.name).join(', ')}',
                      style: const TextStyle(
                        color: Colors.black54,
                        fontSize: 12,
                      ),
                    ),
                  const SizedBox(height: 8),
                  QuantityStepper(
                    value: item.quantity,
                    compact: true,
                    onChanged: (value) =>
                        state.updateCartQuantity(index, value),
                  ),
                ],
              ),
            ),
            Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(
                  peso(item.total),
                  style: const TextStyle(
                    fontWeight: FontWeight.w900,
                    color: _brand,
                  ),
                ),
                IconButton(
                  tooltip: 'Remove',
                  onPressed: () => state.removeCartItem(index),
                  icon: const Icon(Icons.close),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class CheckoutScreen extends StatefulWidget {
  const CheckoutScreen({super.key});

  @override
  State<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends State<CheckoutScreen> {
  final formKey = GlobalKey<FormState>();
  late final TextEditingController customerName;
  late final TextEditingController contactNumber;
  late final TextEditingController address;
  final notes = TextEditingController();
  String paymentMethod = 'cash';
  String pickupMethod = 'in_store';
  bool initialized = false;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (initialized) {
      return;
    }
    final user = AppScope.of(context).currentUser!;
    customerName = TextEditingController(text: user.name);
    contactNumber = TextEditingController(text: user.phone);
    address = TextEditingController(text: user.address);
    initialized = true;
  }

  @override
  void dispose() {
    customerName.dispose();
    contactNumber.dispose();
    address.dispose();
    notes.dispose();
    super.dispose();
  }

  void submit() {
    if (!formKey.currentState!.validate()) {
      return;
    }
    final state = AppScope.of(context);
    final order = state.placeOrder(
      customerName: customerName.text,
      contactNumber: contactNumber.text,
      address: address.text,
      paymentMethod: paymentMethod,
      pickupMethod: pickupMethod,
      notes: notes.text,
    );
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(
        builder: (_) => CustomerOrderDetailsScreen(orderNumber: order.number),
      ),
    );
    showSnack(context, 'Order placed successfully.');
  }

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    return Scaffold(
      appBar: const MilkTeaAppBar(title: 'Checkout'),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Form(
            key: formKey,
            child: Column(
              children: [
                TextFormField(
                  controller: customerName,
                  decoration: const InputDecoration(labelText: 'Full Name'),
                  validator: requiredField,
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: contactNumber,
                  decoration: const InputDecoration(
                    labelText: 'Contact Number',
                  ),
                  validator: requiredField,
                ),
                const SizedBox(height: 12),
                TextFormField(
                  controller: address,
                  maxLines: 2,
                  decoration: const InputDecoration(labelText: 'Address'),
                  validator: requiredField,
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  initialValue: paymentMethod,
                  decoration: const InputDecoration(
                    labelText: 'Payment Method',
                  ),
                  items: const [
                    DropdownMenuItem(
                      value: 'cash',
                      child: Text('Cash on Pickup'),
                    ),
                    DropdownMenuItem(
                      value: 'card',
                      child: Text('Credit/Debit Card'),
                    ),
                  ],
                  onChanged: (value) =>
                      setState(() => paymentMethod = value ?? 'cash'),
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  initialValue: pickupMethod,
                  decoration: const InputDecoration(labelText: 'Pickup Method'),
                  items: const [
                    DropdownMenuItem(
                      value: 'in_store',
                      child: Text('In-Store Pickup'),
                    ),
                    DropdownMenuItem(
                      value: 'drive_thru',
                      child: Text('Drive-Thru'),
                    ),
                  ],
                  onChanged: (value) =>
                      setState(() => pickupMethod = value ?? 'in_store'),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: notes,
                  maxLines: 2,
                  decoration: const InputDecoration(labelText: 'Order Notes'),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          OrderTotals(
            subtotal: state.cartSubtotal,
            tax: state.cartTax,
            total: state.cartTotal,
          ),
          const SizedBox(height: 14),
          FilledButton.icon(
            onPressed: submit,
            icon: const Icon(Icons.check_circle_outline),
            label: const Text('Place Order'),
          ),
        ],
      ),
    );
  }
}

class CustomerOrdersScreen extends StatelessWidget {
  const CustomerOrdersScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final orders = AppScope.of(context).currentUserOrders;
    if (orders.isEmpty) {
      return const EmptyState(
        icon: Icons.receipt_long_outlined,
        title: 'No orders yet',
        message: 'Your orders will appear here after checkout.',
      );
    }

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Text(
          'My Orders',
          style: Theme.of(
            context,
          ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w900),
        ),
        const SizedBox(height: 12),
        ...orders.map(
          (order) => OrderTile(
            order: order,
            onTap: () => Navigator.of(context).push(
              MaterialPageRoute(
                builder: (_) =>
                    CustomerOrderDetailsScreen(orderNumber: order.number),
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class CustomerOrderDetailsScreen extends StatelessWidget {
  const CustomerOrderDetailsScreen({required this.orderNumber, super.key});

  final String orderNumber;

  @override
  Widget build(BuildContext context) {
    final order = AppScope.of(
      context,
    ).orders.firstWhere((item) => item.number == orderNumber);
    return Scaffold(
      appBar: MilkTeaAppBar(title: order.number),
      body: OrderDetailsBody(order: order, isAdmin: false),
    );
  }
}

class AdminDashboardScreen extends StatelessWidget {
  const AdminDashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    final today = DateTime.now();
    final todaysOrders = state.orders
        .where(
          (order) =>
              order.createdAt.year == today.year &&
              order.createdAt.month == today.month &&
              order.createdAt.day == today.day,
        )
        .length;
    final pending = state.orders
        .where((order) => order.status == 'pending')
        .length;

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Text(
          'Admin Dashboard',
          style: Theme.of(
            context,
          ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w900),
        ),
        const SizedBox(height: 12),
        LayoutBuilder(
          builder: (context, constraints) {
            final columns = constraints.maxWidth > 720 ? 4 : 2;
            return GridView.count(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              crossAxisCount: columns,
              crossAxisSpacing: 12,
              mainAxisSpacing: 12,
              childAspectRatio: columns == 4 ? 1.7 : 1.2,
              children: [
                StatCard(
                  label: "Today's Orders",
                  value: '$todaysOrders',
                  icon: Icons.event_available,
                  color: Colors.blue,
                ),
                StatCard(
                  label: 'Pending Orders',
                  value: '$pending',
                  icon: Icons.hourglass_bottom,
                  color: Colors.amber,
                ),
                StatCard(
                  label: 'Total Products',
                  value: '${products.length}',
                  icon: Icons.inventory_2_outlined,
                  color: _success,
                ),
                StatCard(
                  label: 'Total Customers',
                  value:
                      '${state.users.where((user) => user.role == UserRole.customer).length}',
                  icon: Icons.people_outline,
                  color: Colors.cyan,
                ),
              ],
            );
          },
        ),
        const SizedBox(height: 18),
        SectionHeader(
          title: 'Recent Orders',
          icon: Icons.history,
          trailing: const InfoPill(text: 'Auto updates in-app'),
        ),
        if (state.orders.isEmpty)
          const EmptyState(
            icon: Icons.receipt_long_outlined,
            title: 'No recent orders',
            message: 'Orders from checkout will appear here instantly.',
          )
        else
          ...state.orders
              .take(4)
              .map(
                (order) => OrderTile(
                  order: order,
                  showPayment: true,
                  onTap: () => openAdminOrder(context, order.number),
                ),
              ),
      ],
    );
  }
}

class AdminOrdersScreen extends StatelessWidget {
  const AdminOrdersScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final orders = AppScope.of(context).orders;
    if (orders.isEmpty) {
      return const EmptyState(
        icon: Icons.receipt_long_outlined,
        title: 'No orders found',
        message: 'Create a customer order to test admin status updates.',
      );
    }

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Text(
          'Orders',
          style: Theme.of(
            context,
          ).textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.w900),
        ),
        const SizedBox(height: 12),
        ...orders.map(
          (order) => OrderTile(
            order: order,
            showPayment: true,
            onTap: () => openAdminOrder(context, order.number),
          ),
        ),
      ],
    );
  }
}

class AdminOrderDetailsScreen extends StatelessWidget {
  const AdminOrderDetailsScreen({required this.orderNumber, super.key});

  final String orderNumber;

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    final order = state.orders.firstWhere((item) => item.number == orderNumber);
    return Scaffold(
      appBar: MilkTeaAppBar(title: order.number),
      body: OrderDetailsBody(order: order, isAdmin: true),
    );
  }
}

class OrderDetailsBody extends StatelessWidget {
  const OrderDetailsBody({
    required this.order,
    required this.isAdmin,
    super.key,
  });

  final Order order;
  final bool isAdmin;

  @override
  Widget build(BuildContext context) {
    final state = AppScope.of(context);
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: [
            StatusChip(label: order.status, type: ChipType.order),
            StatusChip(label: order.paymentStatus, type: ChipType.payment),
          ],
        ),
        const SizedBox(height: 12),
        SectionCard(
          title: 'Order Items',
          icon: Icons.local_cafe_outlined,
          child: Column(
            children: [
              ...order.items.map(
                (item) => ListTile(
                  contentPadding: EdgeInsets.zero,
                  leading: ClipRRect(
                    borderRadius: BorderRadius.circular(8),
                    child: SizedBox(
                      width: 58,
                      height: 58,
                      child: ProductArt(
                        product: item.product,
                        height: 58,
                        compact: true,
                      ),
                    ),
                  ),
                  title: Text(item.product.name),
                  subtitle: Text(
                    '${item.quantity} x ${peso(item.unitPrice)} | ${item.size.displayName} | Sugar ${item.sugarLevel}',
                  ),
                  trailing: Text(
                    peso(item.total),
                    style: const TextStyle(fontWeight: FontWeight.w800),
                  ),
                ),
              ),
              const Divider(),
              OrderTotals(
                subtotal: order.subtotal,
                tax: order.tax,
                total: order.total,
                embedded: true,
              ),
            ],
          ),
        ),
        SectionCard(
          title: 'Customer Information',
          icon: Icons.person_outline,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                order.customerName,
                style: const TextStyle(fontWeight: FontWeight.w800),
              ),
              const SizedBox(height: 4),
              Text(order.contactNumber),
              Text(order.address),
            ],
          ),
        ),
        if (isAdmin)
          SectionCard(
            title: 'Admin Controls',
            icon: Icons.admin_panel_settings_outlined,
            child: Column(
              children: [
                DropdownButtonFormField<String>(
                  key: ValueKey('order-status-${order.number}-${order.status}'),
                  initialValue: order.status,
                  decoration: const InputDecoration(labelText: 'Order Status'),
                  items: orderStatuses
                      .map(
                        (status) => DropdownMenuItem(
                          value: status,
                          child: Text(labelize(status)),
                        ),
                      )
                      .toList(),
                  onChanged: (value) {
                    if (value != null) {
                      state.updateOrderStatus(order.number, value);
                    }
                  },
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  key: ValueKey(
                    'payment-status-${order.number}-${order.paymentStatus}',
                  ),
                  initialValue: order.paymentStatus,
                  decoration: const InputDecoration(
                    labelText: 'Payment Status',
                  ),
                  items: paymentStatuses
                      .map(
                        (status) => DropdownMenuItem(
                          value: status,
                          child: Text(labelize(status)),
                        ),
                      )
                      .toList(),
                  onChanged: (value) {
                    if (value != null) {
                      state.updatePaymentStatus(order.number, value);
                    }
                  },
                ),
              ],
            ),
          ),
        SectionCard(
          title: 'Order Details',
          icon: Icons.info_outline,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              DetailRow(
                label: 'Order Date',
                value: formatDate(order.createdAt),
              ),
              DetailRow(
                label: 'Payment Method',
                value: labelize(order.paymentMethod),
              ),
              DetailRow(
                label: 'Payment Status',
                value: labelize(order.paymentStatus),
              ),
              DetailRow(
                label: 'Pickup Method',
                value: labelize(order.pickupMethod),
              ),
              if (order.notes.isNotEmpty)
                DetailRow(label: 'Notes', value: order.notes),
            ],
          ),
        ),
      ],
    );
  }
}

class AdminCatalogScreen extends StatelessWidget {
  const AdminCatalogScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 3,
      child: Column(
        children: [
          const TabBar(
            tabs: [
              Tab(icon: Icon(Icons.inventory_2_outlined), text: 'Products'),
              Tab(icon: Icon(Icons.add_circle_outline), text: 'Add-ons'),
              Tab(icon: Icon(Icons.straighten), text: 'Sizes'),
            ],
          ),
          Expanded(
            child: TabBarView(
              children: [
                CatalogList(
                  children: products
                      .map(
                        (product) => AdminProductCatalogCard(product: product),
                      )
                      .toList(),
                ),
                CatalogList(
                  children: addOns
                      .map(
                        (addOn) => ListTile(
                          leading: const Icon(
                            Icons.add_circle_outline,
                            color: _brand,
                          ),
                          title: Text(addOn.name),
                          subtitle: Text(addOn.description),
                          trailing: CatalogTrailingActions(
                            priceText: peso(addOn.price),
                            tooltip: 'Edit ${addOn.name}',
                            onEdit: () => Navigator.of(context).push(
                              MaterialPageRoute(
                                builder: (_) => AddOnEditScreen(addOn: addOn),
                              ),
                            ),
                          ),
                        ),
                      )
                      .toList(),
                ),
                CatalogList(
                  children: sizes
                      .map(
                        (size) => ListTile(
                          leading: const Icon(Icons.straighten, color: _brand),
                          title: Text(size.displayName),
                          subtitle: Text(
                            size.adjustment == 0
                                ? 'Base price'
                                : 'Price adjustment',
                          ),
                          trailing: CatalogTrailingActions(
                            priceText: size.adjustment == 0
                                ? 'Base'
                                : '+${peso(size.adjustment)}',
                            tooltip: 'Edit ${size.displayName}',
                            onEdit: () => Navigator.of(context).push(
                              MaterialPageRoute(
                                builder: (_) => SizeEditScreen(size: size),
                              ),
                            ),
                          ),
                        ),
                      )
                      .toList(),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class AdminProductCatalogCard extends StatelessWidget {
  const AdminProductCatalogCard({required this.product, super.key});

  final Product product;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(8),
            child: ProductArt(product: product, height: 112, compact: true),
          ),
          const SizedBox(height: 12),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      product.name,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontWeight: FontWeight.w900,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      labelize(product.category),
                      style: const TextStyle(color: Colors.black54),
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 10),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  PriceBadge(text: product.formattedPrice),
                  const SizedBox(height: 8),
                  IconButton.filledTonal(
                    tooltip: 'Edit ${product.name}',
                    onPressed: () => Navigator.of(context).push(
                      MaterialPageRoute(
                        builder: (_) =>
                            ProductEditScreen(productId: product.id),
                      ),
                    ),
                    icon: const Icon(Icons.edit_outlined),
                  ),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class ProductEditScreen extends StatefulWidget {
  const ProductEditScreen({required this.productId, super.key});

  final int productId;

  @override
  State<ProductEditScreen> createState() => _ProductEditScreenState();
}

class _ProductEditScreenState extends State<ProductEditScreen> {
  final formKey = GlobalKey<FormState>();
  late Product product;
  late final TextEditingController name;
  late final TextEditingController description;
  late final TextEditingController basePrice;
  late String category;

  @override
  void initState() {
    super.initState();
    product = products.firstWhere((item) => item.id == widget.productId);
    name = TextEditingController(text: product.name);
    description = TextEditingController(text: product.description);
    basePrice = TextEditingController(
      text: product.basePrice.toStringAsFixed(2),
    );
    category = product.category;
  }

  @override
  void dispose() {
    name.dispose();
    description.dispose();
    basePrice.dispose();
    super.dispose();
  }

  void submit() {
    if (!formKey.currentState!.validate()) {
      return;
    }

    AppScope.of(context).updateProduct(
      product.copyWith(
        name: name.text.trim(),
        description: description.text.trim(),
        basePrice: parseAmount(basePrice.text),
        category: category,
      ),
    );
    Navigator.of(context).pop();
    showSnack(context, 'Product updated.');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const MilkTeaAppBar(title: 'Edit Product'),
      body: EditFormShell(
        icon: Icons.inventory_2_outlined,
        title: product.name,
        child: Form(
          key: formKey,
          child: Column(
            children: [
              ProductArt(product: product, height: 140),
              const SizedBox(height: 16),
              TextFormField(
                controller: name,
                decoration: const InputDecoration(labelText: 'Product Name'),
                validator: requiredField,
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: description,
                minLines: 2,
                maxLines: 4,
                decoration: const InputDecoration(labelText: 'Description'),
                validator: requiredField,
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: basePrice,
                keyboardType: const TextInputType.numberWithOptions(
                  decimal: true,
                ),
                decoration: const InputDecoration(labelText: 'Base Price'),
                validator: amountValidator,
              ),
              const SizedBox(height: 12),
              DropdownButtonFormField<String>(
                initialValue: category,
                decoration: const InputDecoration(labelText: 'Category'),
                items: const [
                  DropdownMenuItem(value: 'milk_tea', child: Text('Milk Tea')),
                  DropdownMenuItem(
                    value: 'fruit_tea',
                    child: Text('Fruit Tea'),
                  ),
                  DropdownMenuItem(value: 'coffee', child: Text('Coffee')),
                ],
                onChanged: (value) =>
                    setState(() => category = value ?? 'milk_tea'),
              ),
              const SizedBox(height: 16),
              SaveButton(onPressed: submit),
            ],
          ),
        ),
      ),
    );
  }
}

class AddOnEditScreen extends StatefulWidget {
  const AddOnEditScreen({required this.addOn, super.key});

  final AddOnOption addOn;

  @override
  State<AddOnEditScreen> createState() => _AddOnEditScreenState();
}

class _AddOnEditScreenState extends State<AddOnEditScreen> {
  final formKey = GlobalKey<FormState>();
  late final TextEditingController name;
  late final TextEditingController description;
  late final TextEditingController price;

  @override
  void initState() {
    super.initState();
    name = TextEditingController(text: widget.addOn.name);
    description = TextEditingController(text: widget.addOn.description);
    price = TextEditingController(text: widget.addOn.price.toStringAsFixed(2));
  }

  @override
  void dispose() {
    name.dispose();
    description.dispose();
    price.dispose();
    super.dispose();
  }

  void submit() {
    if (!formKey.currentState!.validate()) {
      return;
    }

    AppScope.of(context).updateAddOn(
      widget.addOn,
      widget.addOn.copyWith(
        name: name.text.trim(),
        description: description.text.trim(),
        price: parseAmount(price.text),
      ),
    );
    Navigator.of(context).pop();
    showSnack(context, 'Add-on updated.');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const MilkTeaAppBar(title: 'Edit Add-on'),
      body: EditFormShell(
        icon: Icons.add_circle_outline,
        title: widget.addOn.name,
        child: Form(
          key: formKey,
          child: Column(
            children: [
              TextFormField(
                controller: name,
                decoration: const InputDecoration(labelText: 'Add-on Name'),
                validator: requiredField,
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: description,
                minLines: 2,
                maxLines: 4,
                decoration: const InputDecoration(labelText: 'Description'),
                validator: requiredField,
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: price,
                keyboardType: const TextInputType.numberWithOptions(
                  decimal: true,
                ),
                decoration: const InputDecoration(labelText: 'Price'),
                validator: amountValidator,
              ),
              const SizedBox(height: 16),
              SaveButton(onPressed: submit),
            ],
          ),
        ),
      ),
    );
  }
}

class SizeEditScreen extends StatefulWidget {
  const SizeEditScreen({required this.size, super.key});

  final SizeOption size;

  @override
  State<SizeEditScreen> createState() => _SizeEditScreenState();
}

class _SizeEditScreenState extends State<SizeEditScreen> {
  final formKey = GlobalKey<FormState>();
  late final TextEditingController displayName;
  late final TextEditingController adjustment;

  @override
  void initState() {
    super.initState();
    displayName = TextEditingController(text: widget.size.displayName);
    adjustment = TextEditingController(
      text: widget.size.adjustment.toStringAsFixed(2),
    );
  }

  @override
  void dispose() {
    displayName.dispose();
    adjustment.dispose();
    super.dispose();
  }

  void submit() {
    if (!formKey.currentState!.validate()) {
      return;
    }

    AppScope.of(context).updateSize(
      widget.size,
      widget.size.copyWith(
        displayName: displayName.text.trim(),
        adjustment: parseAmount(adjustment.text),
      ),
    );
    Navigator.of(context).pop();
    showSnack(context, 'Size updated.');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const MilkTeaAppBar(title: 'Edit Size'),
      body: EditFormShell(
        icon: Icons.straighten,
        title: widget.size.displayName,
        child: Form(
          key: formKey,
          child: Column(
            children: [
              TextFormField(
                initialValue: labelize(widget.size.name),
                readOnly: true,
                decoration: const InputDecoration(labelText: 'Size Code'),
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: displayName,
                decoration: const InputDecoration(labelText: 'Display Name'),
                validator: requiredField,
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: adjustment,
                keyboardType: const TextInputType.numberWithOptions(
                  decimal: true,
                ),
                decoration: const InputDecoration(
                  labelText: 'Price Adjustment',
                ),
                validator: amountValidator,
              ),
              const SizedBox(height: 16),
              SaveButton(onPressed: submit),
            ],
          ),
        ),
      ),
    );
  }
}

class EditFormShell extends StatelessWidget {
  const EditFormShell({
    required this.icon,
    required this.title,
    required this.child,
    super.key,
  });

  final IconData icon;
  final String title;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [SectionCard(title: title, icon: icon, child: child)],
    );
  }
}

class SaveButton extends StatelessWidget {
  const SaveButton({required this.onPressed, super.key});

  final VoidCallback onPressed;

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      child: FilledButton.icon(
        onPressed: onPressed,
        icon: const Icon(Icons.save_outlined),
        label: const Text('Save Changes'),
      ),
    );
  }
}

class CatalogTrailingActions extends StatelessWidget {
  const CatalogTrailingActions({
    required this.priceText,
    required this.tooltip,
    required this.onEdit,
    super.key,
  });

  final String priceText;
  final String tooltip;
  final VoidCallback onEdit;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        PriceBadge(text: priceText),
        const SizedBox(width: 6),
        IconButton.filledTonal(
          tooltip: tooltip,
          onPressed: onEdit,
          icon: const Icon(Icons.edit_outlined),
        ),
      ],
    );
  }
}

class CatalogList extends StatelessWidget {
  const CatalogList({required this.children, super.key});

  final List<Widget> children;

  @override
  Widget build(BuildContext context) {
    return ListView.separated(
      padding: const EdgeInsets.all(16),
      itemCount: children.length,
      separatorBuilder: (context, index) => const SizedBox(height: 8),
      itemBuilder: (context, index) => Card(child: children[index]),
    );
  }
}

class MilkTeaAppBar extends StatelessWidget implements PreferredSizeWidget {
  const MilkTeaAppBar({
    required this.title,
    this.actions = const [],
    super.key,
  });

  final String title;
  final List<Widget> actions;

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      title: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          const Icon(Icons.local_cafe, color: _brand),
          const SizedBox(width: 8),
          Flexible(
            child: Text(
              title,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                fontWeight: FontWeight.w900,
                color: _brandDark,
              ),
            ),
          ),
        ],
      ),
      actions: actions,
    );
  }
}

class BrandHeader extends StatelessWidget {
  const BrandHeader({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        const Icon(Icons.local_cafe, color: _brand, size: 34),
        const SizedBox(width: 10),
        Flexible(
          child: Text(
            'Milk Tea Shop',
            overflow: TextOverflow.ellipsis,
            style: Theme.of(context).textTheme.headlineSmall?.copyWith(
              color: _brandDark,
              fontWeight: FontWeight.w900,
            ),
          ),
        ),
      ],
    );
  }
}

class UserMenu extends StatelessWidget {
  const UserMenu({required this.name, required this.onLogout, super.key});

  final String name;
  final VoidCallback onLogout;

  @override
  Widget build(BuildContext context) {
    return PopupMenuButton<String>(
      tooltip: name,
      onSelected: (value) {
        if (value == 'profile') {
          Navigator.of(
            context,
          ).push(MaterialPageRoute(builder: (_) => const ProfileScreen()));
        }
        if (value == 'logout') {
          onLogout();
        }
      },
      itemBuilder: (context) => [
        PopupMenuItem(enabled: false, child: Text(name)),
        const PopupMenuDivider(),
        const PopupMenuItem(
          value: 'profile',
          child: ListTile(
            leading: Icon(Icons.person_outline),
            title: Text('Profile'),
            contentPadding: EdgeInsets.zero,
          ),
        ),
        const PopupMenuItem(value: 'logout', child: Text('Logout')),
      ],
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 12),
        child: Row(
          children: [
            const Icon(Icons.account_circle_outlined),
            const SizedBox(width: 6),
            ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 120),
              child: Text(name, overflow: TextOverflow.ellipsis),
            ),
            const Icon(Icons.arrow_drop_down),
          ],
        ),
      ),
    );
  }
}

class MenuHero extends StatelessWidget {
  const MenuHero({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      constraints: const BoxConstraints(minHeight: 210),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(8),
        gradient: const LinearGradient(
          colors: [Color(0xFF5E3218), Color(0xFF9C6633), Color(0xFFD39B55)],
        ),
        boxShadow: [
          BoxShadow(
            color: _brand.withValues(alpha: 0.16),
            blurRadius: 26,
            offset: const Offset(0, 12),
          ),
        ],
      ),
      child: Stack(
        children: [
          Positioned(
            right: -8,
            bottom: -12,
            child: SizedBox(
              width: 150,
              height: 180,
              child: CustomPaint(
                painter: CupPainter(
                  liquidColor: const Color(0xFFA6652C),
                  accentColor: const Color(0xFFFFE7B8),
                  showLabel: false,
                ),
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.only(right: 96),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Text(
                  'Freshly mixed drinks',
                  style: TextStyle(
                    color: Color(0xFFFFE7B8),
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const SizedBox(height: 10),
                Text(
                  'Our Menu',
                  style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                    color: Colors.white,
                    fontWeight: FontWeight.w900,
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Choose your favorite milk tea, fruit tea, or coffee and customize it just the way you like.',
                  style: TextStyle(color: Colors.white),
                ),
                const SizedBox(height: 16),
                const Wrap(
                  spacing: 8,
                  runSpacing: 8,
                  children: [
                    HeroTag(
                      icon: Icons.local_cafe_outlined,
                      text: 'Crafted daily',
                    ),
                    HeroTag(
                      icon: Icons.water_drop_outlined,
                      text: 'Sugar control',
                    ),
                    HeroTag(
                      icon: Icons.shopping_bag_outlined,
                      text: 'Easy ordering',
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class HeroTag extends StatelessWidget {
  const HeroTag({required this.icon, required this.text, super.key});

  final IconData icon;
  final String text;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(999),
        border: Border.all(color: Colors.white.withValues(alpha: 0.28)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: Colors.white, size: 16),
          const SizedBox(width: 5),
          Text(
            text,
            style: const TextStyle(
              color: Colors.white,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}

class CategoryFilter extends StatelessWidget {
  const CategoryFilter({
    required this.selected,
    required this.onChanged,
    super.key,
  });

  final String selected;
  final ValueChanged<String> onChanged;

  @override
  Widget build(BuildContext context) {
    const categories = ['all', 'milk_tea', 'fruit_tea', 'coffee'];
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Row(
        children: categories
            .map(
              (category) => Padding(
                padding: const EdgeInsets.only(right: 8),
                child: ChoiceChip(
                  selected: selected == category,
                  label: Text(category == 'all' ? 'All' : labelize(category)),
                  onSelected: (_) => onChanged(category),
                ),
              ),
            )
            .toList(),
      ),
    );
  }
}

class ProductArt extends StatelessWidget {
  const ProductArt({
    required this.product,
    required this.height,
    this.compact = false,
    super.key,
  });

  final Product product;
  final double height;
  final bool compact;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: height,
      width: double.infinity,
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: [
            product.primary.withValues(alpha: 0.72),
            product.secondary.withValues(alpha: 0.86),
          ],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      child: CustomPaint(
        painter: CupPainter(
          liquidColor: product.secondary,
          accentColor: product.primary,
          label: compact ? '' : product.name,
          showLabel: !compact,
        ),
      ),
    );
  }
}

class CupPainter extends CustomPainter {
  const CupPainter({
    required this.liquidColor,
    required this.accentColor,
    this.label = '',
    this.showLabel = true,
  });

  final Color liquidColor;
  final Color accentColor;
  final String label;
  final bool showLabel;

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()..isAntiAlias = true;
    final centerX = size.width / 2;
    final cupWidth = size.width * 0.34;
    final cupHeight = size.height * 0.62;
    final cupTop = size.height * 0.2;
    final cupLeft = centerX - cupWidth / 2;
    final cupRect = Rect.fromLTWH(cupLeft, cupTop, cupWidth, cupHeight);

    paint.color = Colors.white.withValues(alpha: 0.18);
    canvas.drawCircle(
      Offset(size.width * 0.18, size.height * 0.22),
      size.height * 0.16,
      paint,
    );
    canvas.drawCircle(
      Offset(size.width * 0.84, size.height * 0.28),
      size.height * 0.18,
      paint,
    );
    canvas.drawCircle(
      Offset(size.width * 0.22, size.height * 0.78),
      size.height * 0.1,
      paint,
    );
    canvas.drawCircle(
      Offset(size.width * 0.84, size.height * 0.78),
      size.height * 0.12,
      paint,
    );

    paint
      ..color = const Color(0xFF2D241C)
      ..strokeWidth = size.height * 0.045
      ..strokeCap = StrokeCap.round;
    canvas.drawLine(
      Offset(centerX + cupWidth * 0.22, cupTop - size.height * 0.12),
      Offset(centerX + cupWidth * 0.34, cupTop + cupHeight * 0.18),
      paint,
    );

    final strawTop = Rect.fromLTWH(
      centerX + cupWidth * 0.04,
      cupTop - size.height * 0.15,
      cupWidth * 0.42,
      size.height * 0.055,
    );
    canvas.drawRRect(
      RRect.fromRectAndRadius(strawTop, Radius.circular(size.height * 0.04)),
      paint,
    );

    final cupPath = Path()
      ..moveTo(cupRect.left + cupWidth * 0.08, cupRect.top)
      ..lineTo(cupRect.right - cupWidth * 0.08, cupRect.top)
      ..lineTo(cupRect.right - cupWidth * 0.18, cupRect.bottom)
      ..quadraticBezierTo(
        centerX,
        cupRect.bottom + size.height * 0.06,
        cupRect.left + cupWidth * 0.18,
        cupRect.bottom,
      )
      ..close();

    paint.color = Colors.white.withValues(alpha: 0.88);
    canvas.drawPath(cupPath, paint);

    final innerPath = Path()
      ..moveTo(
        cupRect.left + cupWidth * 0.16,
        cupRect.top + size.height * 0.045,
      )
      ..lineTo(
        cupRect.right - cupWidth * 0.16,
        cupRect.top + size.height * 0.045,
      )
      ..lineTo(
        cupRect.right - cupWidth * 0.24,
        cupRect.bottom - size.height * 0.025,
      )
      ..quadraticBezierTo(
        centerX,
        cupRect.bottom + size.height * 0.025,
        cupRect.left + cupWidth * 0.24,
        cupRect.bottom - size.height * 0.025,
      )
      ..close();
    paint.color = accentColor.withValues(alpha: 0.4);
    canvas.drawPath(innerPath, paint);

    final liquidPath = Path()
      ..moveTo(cupRect.left + cupWidth * 0.18, cupRect.top + cupHeight * 0.42)
      ..quadraticBezierTo(
        centerX,
        cupRect.top + cupHeight * 0.36,
        cupRect.right - cupWidth * 0.18,
        cupRect.top + cupHeight * 0.42,
      )
      ..lineTo(
        cupRect.right - cupWidth * 0.24,
        cupRect.bottom - size.height * 0.04,
      )
      ..quadraticBezierTo(
        centerX,
        cupRect.bottom + size.height * 0.012,
        cupRect.left + cupWidth * 0.24,
        cupRect.bottom - size.height * 0.04,
      )
      ..close();
    paint.color = liquidColor.withValues(alpha: 0.86);
    canvas.drawPath(liquidPath, paint);

    paint.color = Colors.white.withValues(alpha: 0.25);
    canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(
          cupLeft + cupWidth * 0.18,
          cupTop + size.height * 0.08,
          cupWidth * 0.18,
          cupHeight * 0.58,
        ),
        Radius.circular(size.height * 0.04),
      ),
      paint,
    );

    paint.color = const Color(0xFF3D2417).withValues(alpha: 0.86);
    for (final pearl in [
      Offset(centerX - cupWidth * 0.23, cupRect.bottom - size.height * 0.13),
      Offset(centerX - cupWidth * 0.02, cupRect.bottom - size.height * 0.08),
      Offset(centerX + cupWidth * 0.16, cupRect.bottom - size.height * 0.14),
    ]) {
      canvas.drawCircle(pearl, size.height * 0.045, paint);
    }

    paint.color = Colors.black.withValues(alpha: 0.18);
    canvas.drawOval(
      Rect.fromCenter(
        center: Offset(centerX, cupRect.bottom + size.height * 0.05),
        width: cupWidth * 1.35,
        height: size.height * 0.08,
      ),
      paint,
    );

    if (showLabel && label.isNotEmpty) {
      final textPainter = TextPainter(
        text: TextSpan(
          text: label,
          style: TextStyle(
            color: Colors.black.withValues(alpha: 0.72),
            fontSize: size.height * 0.07,
            fontWeight: FontWeight.w800,
          ),
        ),
        textAlign: TextAlign.center,
        textDirection: TextDirection.ltr,
        maxLines: 1,
      )..layout(maxWidth: size.width * 0.82);
      textPainter.paint(
        canvas,
        Offset(centerX - textPainter.width / 2, size.height * 0.82),
      );
    }
  }

  @override
  bool shouldRepaint(covariant CupPainter oldDelegate) {
    return oldDelegate.liquidColor != liquidColor ||
        oldDelegate.accentColor != accentColor ||
        oldDelegate.label != label ||
        oldDelegate.showLabel != showLabel;
  }
}

class SectionCard extends StatelessWidget {
  const SectionCard({
    required this.title,
    required this.icon,
    required this.child,
    super.key,
  });

  final String title;
  final IconData icon;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SectionHeader(title: title, icon: icon),
            const SizedBox(height: 12),
            child,
          ],
        ),
      ),
    );
  }
}

class SectionHeader extends StatelessWidget {
  const SectionHeader({
    required this.title,
    required this.icon,
    this.trailing,
    super.key,
  });

  final String title;
  final IconData icon;
  final Widget? trailing;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Icon(icon, color: _brand),
        const SizedBox(width: 8),
        Expanded(
          child: Text(
            title,
            style: Theme.of(
              context,
            ).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.w900),
          ),
        ),
        ?trailing,
      ],
    );
  }
}

class QuantityStepper extends StatelessWidget {
  const QuantityStepper({
    required this.value,
    required this.onChanged,
    this.compact = false,
    super.key,
  });

  final int value;
  final ValueChanged<int> onChanged;
  final bool compact;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        IconButton.filledTonal(
          visualDensity: compact
              ? VisualDensity.compact
              : VisualDensity.standard,
          onPressed: value <= 1 ? null : () => onChanged(value - 1),
          icon: const Icon(Icons.remove),
        ),
        SizedBox(
          width: compact ? 34 : 46,
          child: Text(
            '$value',
            textAlign: TextAlign.center,
            style: const TextStyle(fontWeight: FontWeight.w900),
          ),
        ),
        IconButton.filledTonal(
          visualDensity: compact
              ? VisualDensity.compact
              : VisualDensity.standard,
          onPressed: value >= 50 ? null : () => onChanged(value + 1),
          icon: const Icon(Icons.add),
        ),
      ],
    );
  }
}

class SummaryBar extends StatelessWidget {
  const SummaryBar({
    required this.label,
    required this.value,
    required this.action,
    super.key,
  });

  final String label;
  final String value;
  final Widget action;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(
              children: [
                Expanded(
                  child: Text(
                    label,
                    style: const TextStyle(fontWeight: FontWeight.w800),
                  ),
                ),
                Text(
                  value,
                  style: Theme.of(context).textTheme.titleLarge?.copyWith(
                    color: _brand,
                    fontWeight: FontWeight.w900,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            action,
          ],
        ),
      ),
    );
  }
}

class OrderTotals extends StatelessWidget {
  const OrderTotals({
    required this.subtotal,
    required this.tax,
    required this.total,
    this.embedded = false,
    super.key,
  });

  final double subtotal;
  final double tax;
  final double total;
  final bool embedded;

  @override
  Widget build(BuildContext context) {
    final content = Column(
      children: [
        DetailLine(label: 'Subtotal', value: peso(subtotal)),
        DetailLine(label: 'Tax (8%)', value: peso(tax)),
        const Divider(),
        DetailLine(label: 'Total', value: peso(total), strong: true),
      ],
    );
    if (embedded) {
      return content;
    }
    return Card(
      child: Padding(padding: const EdgeInsets.all(14), child: content),
    );
  }
}

class DetailLine extends StatelessWidget {
  const DetailLine({
    required this.label,
    required this.value,
    this.strong = false,
    super.key,
  });

  final String label;
  final String value;
  final bool strong;

  @override
  Widget build(BuildContext context) {
    final style = TextStyle(
      fontWeight: strong ? FontWeight.w900 : FontWeight.w600,
      color: strong ? _brand : null,
      fontSize: strong ? 17 : null,
    );
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Expanded(child: Text(label, style: style)),
          Text(value, style: style),
        ],
      ),
    );
  }
}

class DetailRow extends StatelessWidget {
  const DetailRow({required this.label, required this.value, super.key});

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(color: Colors.black54, fontSize: 12),
          ),
          const SizedBox(height: 2),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w700)),
        ],
      ),
    );
  }
}

class OrderTile extends StatelessWidget {
  const OrderTile({
    required this.order,
    required this.onTap,
    this.showPayment = false,
    super.key,
  });

  final Order order;
  final VoidCallback onTap;
  final bool showPayment;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        onTap: onTap,
        title: Text(
          order.number,
          style: const TextStyle(fontWeight: FontWeight.w900),
        ),
        subtitle: Text(
          '${order.customerName} | ${formatDate(order.createdAt)}',
        ),
        trailing: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            Text(
              peso(order.total),
              style: const TextStyle(fontWeight: FontWeight.w900),
            ),
            const SizedBox(height: 4),
            Wrap(
              spacing: 4,
              children: [
                StatusChip(
                  label: order.status,
                  type: ChipType.order,
                  compact: true,
                ),
                if (showPayment)
                  StatusChip(
                    label: order.paymentStatus,
                    type: ChipType.payment,
                    compact: true,
                  ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class StatCard extends StatelessWidget {
  const StatCard({
    required this.label,
    required this.value,
    required this.icon,
    required this.color,
    super.key,
  });

  final String label;
  final String value;
  final IconData icon;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Card(
      color: color,
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    label,
                    style: const TextStyle(
                      color: Colors.white,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  Text(
                    value,
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      color: Colors.white,
                      fontWeight: FontWeight.w900,
                    ),
                  ),
                ],
              ),
            ),
            Icon(icon, color: Colors.white, size: 34),
          ],
        ),
      ),
    );
  }
}

class PriceBadge extends StatelessWidget {
  const PriceBadge({required this.text, super.key});

  final String text;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 5),
      decoration: BoxDecoration(
        color: Theme.of(context).colorScheme.primary,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(
        text,
        style: const TextStyle(
          color: Colors.white,
          fontWeight: FontWeight.w900,
          fontSize: 12,
        ),
      ),
    );
  }
}

enum ChipType { order, payment }

class StatusChip extends StatelessWidget {
  const StatusChip({
    required this.label,
    required this.type,
    this.compact = false,
    super.key,
  });

  final String label;
  final ChipType type;
  final bool compact;

  Color get color {
    if (type == ChipType.payment) {
      return switch (label) {
        'paid' => _success,
        'failed' => Colors.red,
        'refunded' => Colors.blueGrey,
        _ => _warning,
      };
    }

    return switch (label) {
      'confirmed' => Colors.cyan,
      'preparing' => Colors.blue,
      'ready' => _success,
      'completed' => Colors.blueGrey,
      'cancelled' => Colors.red,
      _ => _warning,
    };
  }

  @override
  Widget build(BuildContext context) {
    final foreground = color == _warning ? Colors.black : Colors.white;
    return Container(
      padding: EdgeInsets.symmetric(
        horizontal: compact ? 6 : 10,
        vertical: compact ? 3 : 6,
      ),
      decoration: BoxDecoration(
        color: color,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Text(
        labelize(label),
        style: TextStyle(
          color: foreground,
          fontWeight: FontWeight.w900,
          fontSize: compact ? 10 : 12,
        ),
      ),
    );
  }
}

class InfoPill extends StatelessWidget {
  const InfoPill({required this.text, super.key});

  final String text;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: _cream,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: _brand.withValues(alpha: 0.18)),
      ),
      child: Text(
        text,
        style: const TextStyle(color: _brandDark, fontWeight: FontWeight.w700),
      ),
    );
  }
}

class EmptyState extends StatelessWidget {
  const EmptyState({
    required this.icon,
    required this.title,
    required this.message,
    super.key,
  });

  final IconData icon;
  final String title;
  final String message;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(28),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 58, color: Colors.black26),
            const SizedBox(height: 14),
            Text(
              title,
              style: Theme.of(
                context,
              ).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.w900),
            ),
            const SizedBox(height: 6),
            Text(
              message,
              textAlign: TextAlign.center,
              style: const TextStyle(color: Colors.black54),
            ),
          ],
        ),
      ),
    );
  }
}

String? requiredField(String? value) {
  if (value == null || value.trim().isEmpty) {
    return 'Required';
  }
  return null;
}

String? amountValidator(String? value) {
  if (value == null || value.trim().isEmpty) {
    return 'Required';
  }
  final amount = double.tryParse(value.trim().replaceAll(',', ''));
  if (amount == null || amount < 0) {
    return 'Enter a valid amount';
  }
  return null;
}

double parseAmount(String value) {
  return double.parse(value.trim().replaceAll(',', ''));
}

String peso(double value) => 'PHP ${value.toStringAsFixed(2)}';

String two(int value) => value.toString().padLeft(2, '0');

String labelize(String value) {
  return value
      .split('_')
      .where((part) => part.isNotEmpty)
      .map((part) => part[0].toUpperCase() + part.substring(1))
      .join(' ');
}

String formatDate(DateTime value) {
  const months = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec',
  ];
  return '${months[value.month - 1]} ${value.day}, ${value.year} ${two(value.hour)}:${two(value.minute)}';
}

void showSnack(BuildContext context, String message) {
  ScaffoldMessenger.of(context)
    ..clearSnackBars()
    ..showSnackBar(SnackBar(content: Text(message)));
}

void openProduct(BuildContext context, Product product) {
  Navigator.of(context).push(
    MaterialPageRoute(builder: (_) => ProductDetailScreen(product: product)),
  );
}

void openAdminOrder(BuildContext context, String orderNumber) {
  Navigator.of(context).push(
    MaterialPageRoute(
      builder: (_) => AdminOrderDetailsScreen(orderNumber: orderNumber),
    ),
  );
}
