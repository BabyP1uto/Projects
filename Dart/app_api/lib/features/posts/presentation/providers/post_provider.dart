import 'package:flutter_riverpod/flutter_riverpod.dart';  // Основной пакет Riverpod
import '../../data/repositories/post_repository.dart';
import '../../data/models/post_model.dart';

final postRepositoryProvider = Provider((ref) => PostRepository());

final postsProvider = FutureProvider<List<PostModel>>((ref) async {
  final repository = ref.read(postRepositoryProvider);
  return await repository.getPosts();
});