import 'package:blog_flutter/models/posts_model.dart';
import 'package:blog_flutter/pages/post.dart';
import 'package:flutter/material.dart';
import 'package:flutter_html/flutter_html.dart';

class HomePage extends StatefulWidget {
  HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  late Future<List<Posts>> posts;
  bool isLoading = false;
  int currentPage = 1;
  ScrollController _scrollController = ScrollController();
  // String keywords = "";
  String confirmedKeywords = "";
  TextEditingController textController = TextEditingController();

  @override
  void initState() {
    super.initState();
    posts = Posts.fetchPosts();
    _scrollController.addListener(() {
      if (_scrollController.position.pixels ==
          _scrollController.position.maxScrollExtent) {
        currentPage++;

        posts = Posts.appendElements(
            currentList: posts, page: currentPage, keyword: confirmedKeywords);
        setState(() {});
      }
    });
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  void runSearch() {
    confirmedKeywords = textController.text;
    currentPage = 1;
    posts = Posts.fetchPosts(keyword: confirmedKeywords);
    setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: appBar(),
        backgroundColor: Colors.white,
        body: Column(
          children: [
            search(),
            const SizedBox(
              height: 30,
            ),
            Expanded(
              child: FutureBuilder<List<Posts>>(
                // future: Post.fetchPosts(page: currentPage),
                future: posts,
                builder: (context, snapshot) {
                  if (snapshot.hasData) {
                    List<Posts>? data = snapshot.data;
                    return GridView.builder(
                      controller: _scrollController,
                      itemCount: data!.length,
                      padding: const EdgeInsets.only(left: 15, right: 15),
                      gridDelegate: SliverGridDelegateWithMaxCrossAxisExtent(
                        maxCrossAxisExtent: 300,
                        mainAxisSpacing: 10,
                        crossAxisSpacing: 10,
                        childAspectRatio: 0.7,
                      ),
                      itemBuilder: (BuildContext context, int index) {
                        return postComponent(data, index);
                      },
                    );
                  } else if (snapshot.hasError) {
                    return Text(snapshot.error.toString());
                  }
                  return Center(child: CircularProgressIndicator());
                },
              ),
            ),
          ],
        ));
  }

  Container postComponent(List<Posts> data, int index) {
    return Container(
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
                  child: data[index].image != null
                      ? Image.network(
                          data[index].image!.url.toString(),
                          fit: BoxFit.fitWidth,
                        )
                      : Image.asset(
                          'assets/images/placeholder.jpg',
                          fit: BoxFit.fitWidth,
                        ),
                ),
              ),
              Text(
                data[index].tags != null ? data[index].tags!.join("\n") : "",
                style: const TextStyle(
                  fontWeight: FontWeight.w800,
                  color: Colors.black,
                ),
              )
            ],
          ),
          GestureDetector(
            onTap: () {
              // Navigate to the post page with the post ID
              Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (context) {
                    return PostPage(data[index].id);
                  },
                ),
              );
            },
            child: Stack(
              children: [
                Text(
                  data[index].title,
                  style: TextStyle(
                    fontWeight: FontWeight.w800,
                    foreground: Paint()
                      ..style = PaintingStyle.stroke
                      ..strokeWidth = 4
                      ..color = Colors.white,
                  ),
                ),
                Text(
                  data[index].title,
                  style: const TextStyle(
                    fontWeight: FontWeight.w800,
                    color: Colors.black,
                  ),
                ),
              ],
            ),
          ),
          Html(data: data[index].description ?? "")
        ],
      ),
    );
  }

  Container search() {
    return Container(
      margin: const EdgeInsets.only(top: 40, left: 20, right: 20),
      decoration: BoxDecoration(
        boxShadow: [
          BoxShadow(
            color: Colors.amber.withOpacity(0.3),
            blurRadius: 30,
            spreadRadius: 0.0,
          ),
        ],
      ),
      child: TextField(
        controller: textController,
        decoration: InputDecoration(
          hintText: "Search",
          hintStyle: const TextStyle(color: Color.fromARGB(66, 31, 19, 19)),
          filled: true,
          // prefixIcon: const Icon(Icons.search),
          suffixIcon: GestureDetector(
            onTap: () {
              print("search");
              runSearch();
            },
            child: SizedBox(
              width: 100,
              child: IntrinsicHeight(
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    VerticalDivider(
                      color: Colors.black,
                      indent: 10,
                      endIndent: 10,
                      thickness: 0.1,
                    ),
                    Padding(
                      padding: EdgeInsets.all(8),
                      child: Icon(Icons.search),
                    )
                  ],
                ),
              ),
            ),
          ),
          fillColor: Colors.white,
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(30)),
        ),
      ),
    );
  }

  AppBar appBar() {
    return AppBar(
      flexibleSpace: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
              begin: Alignment.topRight,
              end: Alignment.bottomLeft,
              colors: <Color>[Colors.red, Colors.green]),
        ),
      ),
      title: const Text(
        "Hello World!",
        style: TextStyle(fontWeight: FontWeight.w100),
      ),
      elevation: 5.0,
      centerTitle: true,
      leading: GestureDetector(
        onTap: () {
          debugPrint("leading");
        },
        child: Container(
          margin: const EdgeInsets.all(5),
          decoration: BoxDecoration(
              color: Colors.white, borderRadius: BorderRadius.circular(70)),
          child: const Icon(
            Icons.arrow_back,
            color: Colors.black,
          ),
        ),
      ),
      actions: [
        GestureDetector(
          onTap: () {
            debugPrint("trail");
          },
          child: Container(
              margin: const EdgeInsets.all(5),
              width: 37,
              alignment: Alignment.center,
              decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(999)),
              child: const Icon(Icons.more)),
        )
      ],
    );
  }
}
