import 'package:blog_flutter/models/comments_meta_model.dart';
import 'package:blog_flutter/models/image_model.dart';
import 'package:blog_flutter/models/user_model.dart';

class Comments {
  final int id;
  final String title;
  final String? description;
  final List<String>? tags;
  final List<Comments>? comments;
  final CommentsMeta? commentsMeta;
  final User? user;
  final ImageModel? image;
  final String createdAt;
  final String updatedAt;

  Comments({
    required this.id,
    required this.title,
    this.description,
    this.tags,
    this.comments,
    this.commentsMeta,
    this.user,
    this.image,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Comments.fromJson(Map<String, dynamic> json) {
    return Comments(
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
      image: json['image'] != null ? ImageModel.fromJson(json['image']) : null,
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }
}
