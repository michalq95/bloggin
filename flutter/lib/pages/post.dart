import 'package:blog_flutter/models/post_model.dart';
import 'package:flutter/material.dart';
import 'package:flutter_html/flutter_html.dart';

class PostPage extends StatefulWidget {
  final int postId;

  PostPage(this.postId);

  @override
  State<PostPage> createState() => _PostPageState();
}

class _PostPageState extends State<PostPage> {
  late Future<Post> post;

  @override
  void initState() {
    super.initState();
    post = Post.fetchPost(id: widget.postId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text('Post Details'),
        ),
        body: FutureBuilder<Post>(
          future: post,
          builder: (context, snapshot) {
            if (snapshot.hasData) {
              Post? data = snapshot.data;
              return SingleChildScrollView(
                child: Container(
                  padding: EdgeInsets.all(16.0),
                  decoration: BoxDecoration(
                    color: Colors.amber,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceAround,
                        children: [
                          Container(
                            width: 70,
                            height: 70,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              shape: BoxShape.circle,
                            ),
                            child: ClipOval(
                              child: (data?.image != null &&
                                      data!.image!.isNotEmpty)
                                  ? Image.network(
                                      data.image![0].url.toString(),
                                      fit: BoxFit.fitWidth,
                                    )
                                  : Image.asset(
                                      'assets/images/placeholder.jpg',
                                      fit: BoxFit.fitWidth,
                                    ),
                            ),
                          ),
                          Expanded(
                            child: Text(
                              data?.tags != null ? data!.tags!.join("\n") : "",
                              style: const TextStyle(
                                fontWeight: FontWeight.w800,
                                color: Colors.black,
                              ),
                            ),
                          )
                        ],
                      ),
                      SizedBox(height: 16),
                      Container(
                        child: Stack(
                          children: [
                            Text(
                              data?.title ?? "",
                              style: TextStyle(
                                fontWeight: FontWeight.w800,
                                foreground: Paint()
                                  ..style = PaintingStyle.stroke
                                  ..strokeWidth = 4
                                  ..color = Colors.white,
                              ),
                            ),
                            Text(
                              data?.title ?? "",
                              style: const TextStyle(
                                fontWeight: FontWeight.w800,
                                color: Colors.black,
                              ),
                            ),
                          ],
                        ),
                      ),
                      SizedBox(height: 16),
                      SingleChildScrollView(
                        child: Html(data: data?.description ?? ""),
                      ),
                    ],
                  ),
                ),
              );
            } else if (snapshot.hasError) {
              return Text(snapshot.error.toString());
            }
            return Center(child: CircularProgressIndicator());
          },
        ));
  }
}
