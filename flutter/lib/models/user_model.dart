import 'package:blog_flutter/models/image_model.dart';

class User {
  final int? id;
  final String? name;
  final ImageModel? image;

  User({
    required this.id,
    required this.name,
    required this.image,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      image: json['image'] != null ? ImageModel.fromJson(json['image']) : null,
    );
  }

  // static Future<List<User>> fetchUser() async {
  //   final response =
  //       await http.get(Uri.parse('https://jsonplaceholder.typicode.com/users'));

  //   if (response.statusCode == 200) {
  //     List<dynamic> body = jsonDecode(response.body);
  //     List<User> users =
  //         body.map((dynamic item) => User.fromJson(item)).toList();
  //     return users;
  //   } else {
  //     throw Exception('Failed to load users');
  //   }
  // }
}
