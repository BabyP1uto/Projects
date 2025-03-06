import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'todo_bloc.dart';
import 'todo.dart';

class HomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Todo List'),
      ),
      body: BlocBuilder<TodoBloc, TodoState>(
        builder: (context, state) {
          return ListView.builder(
            itemCount: state.todos.length,
            itemBuilder: (context, index) {
              final todo = state.todos[index];
              return ListTile(
                title: Text(todo.title),
                subtitle: Text(todo.text),
                trailing: IconButton(
                  icon: Icon(Icons.delete),
                  onPressed: () {
                    context.read<TodoBloc>().add(RemoveTodoEvent(todo.id));
                  },
                ),
              );
            },
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => AddTodoScreen()),
          );
        },
        child: Icon(Icons.add),
      ),
    );
  }
}