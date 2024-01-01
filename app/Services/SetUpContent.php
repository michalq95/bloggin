<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Post;
use App\Models\Uploads;

class SetUpContent
{

    public function addContent(Post $post, array $arrayContent)
    {
        $newContent = [];
        foreach ($arrayContent as $index => $content) {
            // if (!is_string($content) && $upload = Uploads::find($content)) {
            if (preg_match('/^__upload(\d+)$/', $content, $matches)) {
                $id = $matches[1];
                $upload = Uploads::find($id);
                if ($upload) {
                    $createdContent = Content::create([
                        'uploads_id' => $id,
                        'post_id' => $post->id,
                        'order' => $index
                    ]);
                    $newContent[] = $createdContent;
                    $createdContent->addUploadToContent($upload);
                }
            } else {
                $newContent[] = Content::create([
                    'text' => $content,
                    'post_id' => $post->id,
                    'order' => $index
                ]);
            }
        }
        return $newContent;
    }

    public function updateContent(Post $post, array $arrayContent)
    {

        $currentContent = $post->content()->pluck('id')->toArray();
        $newContent = [];

        foreach ($arrayContent as $index => $content) {
            if (preg_match('/^__upload(\d+)$/', $content, $matches)) {
                $id = $matches[1];
                if ($content = Content::find($id && $content->post_id == $post->id)) {
                    $new = $content->update([
                        'order' => $index
                    ]);
                    $newContent[] = $new->id;
                }
            } elseif (preg_match('/^__content(\d+)$/', $content, $matches)) {
                $id = $matches[1];
                if ($upload = Uploads::find($id)) {
                    $new =  Content::create([
                        'uploads_id' => $id,
                        'post_id' => $post->id,
                        'order' => $index
                    ]);
                    $newContent[] = $new->id;
                    $new->addUploadToContent($upload);
                }
            } else {
                $new = Content::create([
                    'text' => $content,
                    'post_id' => $post->id,
                    'order' => $index
                ]);
                $newContent[] = $new->id;
            }
        }

        $contentToRemove = array_diff($currentContent, $newContent);

        foreach ($contentToRemove as $contentId) {
            if ($content = Content::find($contentId)) {
                $content->post_id = null;
                $content->save();
            }
        }

        return $newContent;
    }
}
