import 'package:uuid/uuid.dart';

class Todo {
  final String id;
  final String title;
  final String text;

  Todo({
    required this.title,
    required this.text,
  }) : id = const Uuid().v4();

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is Todo &&
          runtimeType == other.runtimeType &&
          id == other.id;

  @override
  int get hashCode => id.hashCode;
}