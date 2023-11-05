class CommentsMeta {
  final int page;
  final bool hasNextPage;
  // final String model;

  CommentsMeta({
    required this.page,
    required this.hasNextPage,
    // required this.model,
  });

  factory CommentsMeta.fromJson(Map<String, dynamic> json) {
    return CommentsMeta(
      page: json['page'],
      hasNextPage: json['has_next_page'],
      // model: json['model'],
    );
  }
}
