import 'dart:convert';
import 'package:blog_flutter/models/image_model.dart';
import 'package:blog_flutter/models/user_model.dart';
import 'package:flutter/material.dart';

import 'package:http/http.dart' as http;

class Posts {
  final int id;
  final String title;
  final String? description;
  final List<String>? tags;
  final int commentsCount;
  final User? user;
  final ImageModel? image;
  final String createdAt;
  final String updatedAt;

  Posts({
    required this.id,
    required this.title,
    required this.description,
    required this.tags,
    required this.commentsCount,
    required this.user,
    required this.image,
    required this.createdAt,
    required this.updatedAt,
  });
  factory Posts.fromJson(Map<String, dynamic> json) {
    return Posts(
      id: json['id'],
      title: json['title'],
      description: json['description'],
      tags: json['tags'] != null ? List<String>.from(json['tags']) : null,
      commentsCount: json['comments_count'],
      user: json['user'] != null ? User.fromJson(json['user']) : null,
      image: json['image'] != null ? ImageModel.fromJson(json['image']) : null,
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  static Future<List<Posts>> fetchPosts({page = 1, keyword = ""}) async {
    final response = await http.get(Uri.parse(
        'http://127.0.0.1:8081/api/post?page=$page&keyword=${keyword}'));
    if (response.statusCode == 200) {
      Map<String, dynamic> responseBody = jsonDecode(response.body);
      List<dynamic> body = responseBody['data'];
      List<Posts> posts =
          body.map((dynamic item) => Posts.fromJson(item)).toList();
      return posts;
    } else {
      throw Exception('Failed to load posts');
    }
  }

  static Future<List<Posts>> appendElements(
      {required Future<List<Posts>> currentList,
      required int page,
      required String keyword}) async {
    final list = await currentList;
    List<Posts> elementsToAdd = await fetchPosts(page: page, keyword: keyword);
    list.addAll(elementsToAdd);
    return list;
  }
}
