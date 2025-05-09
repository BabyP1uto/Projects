import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'pages/auth_page.dart';
import 'pages/home_page.dart';
import 'services/auth_service.dart';

void main() {
  runApp(
    ChangeNotifierProvider(
      create: (context) => AuthService(),
      child: const MyApp(),
    ),
  );
}

class MyApp extends StatefulWidget {
  const MyApp({super.key});

  @override
  State<MyApp> createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  @override
  void initState() {
    super.initState();
    // Check login status when app starts
    Provider.of<AuthService>(context, listen: false).checkLoginStatus();
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'User Profile App',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: Consumer<AuthService>(
        builder: (context, authService, child) {
          return authService.isLoggedIn ? const HomePage() : const AuthPage();
        },
      ),
    );
  }
}