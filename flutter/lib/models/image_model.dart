class ImageModel {
  final int id;
  final String? url;
  final int imageableId;
  final String imageableType;
  final String createdAt;
  final String updatedAt;

  ImageModel({
    required this.id,
    required this.url,
    required this.imageableId,
    required this.imageableType,
    required this.createdAt,
    required this.updatedAt,
  });

  factory ImageModel.fromJson(Map<String, dynamic> json) {
    return ImageModel(
      id: json['id'],
      url: json['url'],
      imageableId: json['imageable_id'],
      imageableType: json['imageable_type'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }
}
