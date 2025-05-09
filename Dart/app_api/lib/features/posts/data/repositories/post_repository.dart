import 'package:dio/dio.dart';

import '../models/post_model.dart';
import '../../../../core/api_client.dart';

class PostRepository {
  final Dio _dio;

  PostRepository({Dio? dio}) : _dio = dio ?? ApiClient().dio;

  Future<List<PostModel>> getPosts() async {
    try {
      final response = await _dio.get('/posts');
      return (response.data as List)
          .map((postJson) => PostModel.fromJson(postJson))
          .toList();
    } on DioException catch (e) {
      throw Exception('Failed to load posts: ${e.message}');
    }
  }
}