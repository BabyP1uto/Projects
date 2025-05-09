import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';

class AuthService with ChangeNotifier {
  User? _currentUser;
  bool _isLoggedIn = false;

  User? get currentUser => _currentUser;
  bool get isLoggedIn => _isLoggedIn;

  Future<void> login(User user) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('user_data', user.toMap().toString());
    await prefs.setBool('is_logged_in', true);
    
    _currentUser = user;
    _isLoggedIn = true;
    notifyListeners();
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('user_data');
    await prefs.setBool('is_logged_in', false);
    
    _currentUser = null;
    _isLoggedIn = false;
    notifyListeners();
  }

  Future<void> checkLoginStatus() async {
    final prefs = await SharedPreferences.getInstance();
    _isLoggedIn = prefs.getBool('is_logged_in') ?? false;
    
    if (_isLoggedIn) {
      final userData = prefs.getString('user_data');
      if (userData != null) {
        try {
          // Convert string representation of map back to map
          final map = Map<String, dynamic>.from(eval(userData));
          _currentUser = User.fromMap(map);
        } catch (e) {
          _isLoggedIn = false;
        }
      }
    }
    notifyListeners();
  }

  // Helper function to parse string representation of map
  dynamic eval(String source) {
    final data = {};
    source.substring(1, source.length - 1).split(', ').forEach((pair) {
      final keyValue = pair.split(': ');
      if (keyValue.length == 2) {
        data[keyValue[0]] = keyValue[1];
      }
    });
    return data;
  }
}