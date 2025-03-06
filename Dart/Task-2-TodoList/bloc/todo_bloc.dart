import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import 'todo.dart';

// События
abstract class TodoEvent extends Equatable {
  @override
  List<Object?> get props => [];
}

class AddTodoEvent extends TodoEvent {
  final Todo todo;

  AddTodoEvent(this.todo);

  @override
  List<Object?> get props => [todo];
}

class RemoveTodoEvent extends TodoEvent {
  final String id;

  RemoveTodoEvent(this.id);

  @override
  List<Object?> get props => [id];
}

// Состояние
class TodoState extends Equatable {
  final List<Todo> todos;

  const TodoState(this.todos);

  @override
  List<Object?> get props => [todos];
}

// BLoC
class TodoBloc extends Bloc<TodoEvent, TodoState> {
  TodoBloc() : super(const TodoState([]));

  @override
  Stream<TodoState> mapEventToState(TodoEvent event) async* {
    if (event is AddTodoEvent) {
      final newTodos = List<Todo>.from(state.todos)..add(event.todo);
      yield TodoState(newTodos);
    } else if (event is RemoveTodoEvent) {
      final newTodos = state.todos.where((todo) => todo.id != event.id).toList();
      yield TodoState(newTodos);
    }
  }
}