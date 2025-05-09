import 'package:flutter/material.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Static Resources App',
      theme: ThemeData(
        fontFamily: 'CustomFont', // Использование кастомного шрифта
        primarySwatch: Colors.blue,
      ),
      home: ImageScreen(),
    );
  }
}

class ImageScreen extends StatelessWidget {
  const ImageScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Изображения и шрифты', style: TextStyle(fontSize: 24)),
      ),
      body: Column(
        children: [
          Image.asset('assets/images/image1.png'),
          SizedBox(height: 10),
          Image.asset('assets/images/image2.png'),
          SizedBox(height: 10),
          Image.asset('assets/images/image3.png'),
          SizedBox(height: 20),
          Text(
            'Этот текст использует кастомный шрифт!',
            style: TextStyle(fontSize: 18),
          ),
        ],
      ),
    );
  }
}