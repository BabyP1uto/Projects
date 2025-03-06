import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'todo_bloc.dart';
import 'todo.dart';

class AddTodoScreen extends StatelessWidget {
  final _titleController = TextEditingController();
  final _textController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Add Todo'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            TextField(
              controller: _titleController,
              decoration: InputDecoration(labelText: 'Title'),
            ),
            TextField(
              controller: _textController,
              decoration: InputDecoration(labelText: 'Text'),
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                final title = _titleController.text;
                final text = _textController.text;
                if (title.isNotEmpty && text.isNotEmpty) {
                  final todo = Todo(title: title, text: text);
                  context.read<TodoBloc>().add(AddTodoEvent(todo));
                  Navigator.pop(context);
                }
              },
              child: Text('Add Todo'),
            ),
          ],
        ),
      ),
    );
  }
}