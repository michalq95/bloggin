import 'package:blog_flutter/models/comments_meta_model.dart';
import 'package:blog_flutter/models/comments_model.dart';
import 'package:blog_flutter/models/image_model.dart';
import 'package:blog_flutter/models/user_model.dart';

import 'dart:convert';
import 'package:http/http.dart' as http;

class Post {
  final int id;
  final String title;
  final String? description;
  final List<Comments>? comments;
  final CommentsMeta? commentsMeta;
  final List<String>? tags;
  final User? user;
  final List<ImageModel>? image;
  final String createdAt;
  final String updatedAt;

  Post({
    required this.id,
    required this.title,
    required this.description,
    required this.tags,
    required this.comments,
    required this.commentsMeta,
    required this.user,
    required this.image,
    required this.createdAt,
    required this.updatedAt,
  });
  factory Post.fromJson(Map<String, dynamic> json) {
    return Post(
      id: json['id'],
      title: json['title'],
      description: json['description'],
      tags: json['tags'] != null ? List<String>.from(json['tags']) : null,
      comments: json['comments'] != null
          ? List<Comments>.from(
              json["comments"].map((x) => Comments.fromJson(x)))
          : null,
      commentsMeta: json['comments_meta'] != null
          ? CommentsMeta.fromJson(json['comments_meta'])
          : null,
      user: json['user'] != null ? User.fromJson(json['user']) : null,
      image: json['image'] != null
          ? List<ImageModel>.from(
              json['image'].map((x) => ImageModel.fromJson(x)))
          : null,
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  static Future<Post> fetchPost({required int id}) async {
    final response =
        await http.get(Uri.parse('http://127.0.0.1:8081/api/post/$id'));
    if (response.statusCode == 200) {
      Map<String, dynamic> responseBody = jsonDecode(response.body);
      var data = responseBody['data'];
      Post post = Post.fromJson(data);
      return post;
    } else {
      throw Exception('Failed to load posts');
    }
  }
}
