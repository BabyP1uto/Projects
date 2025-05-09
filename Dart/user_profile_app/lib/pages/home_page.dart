import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/auth_service.dart';

class HomePage extends StatelessWidget {
  const HomePage({super.key});

  @override
  Widget build(BuildContext context) {
    final authService = Provider.of<AuthService>(context);
    final user = authService.currentUser;

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Profile'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              await authService.logout();
            },
          ),
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Name: ${user?.name ?? ''}', style: const TextStyle(fontSize: 18)),
            const SizedBox(height: 10),
            Text('Email: ${user?.email ?? ''}', style: const TextStyle(fontSize: 18)),
            const SizedBox(height: 10),
            Text('Phone: ${user?.phone ?? ''}', style: const TextStyle(fontSize: 18)),
            const SizedBox(height: 10),
            Text('Address: ${user?.address ?? ''}', style: const TextStyle(fontSize: 18)),
          ],
        ),
      ),
    );
  }
}