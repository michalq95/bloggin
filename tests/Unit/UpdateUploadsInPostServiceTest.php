<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Services\UploadProcessing\UpdateUploadsInPostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUploadsInPostServiceTest extends TestCase
{
    public function testAddAndRemoveCorrectUploads()
    {
        $postMock = $this->getMockBuilder(Post::class)
            ->onlyMethods(['removeUpload', 'addUploads'])
            ->getMock();

        $newUploadIds = [2, 3, 4, 5, 7];
        $currentUploadIds = [1, 2, 3];

        $postMock->expects($this->exactly(1))
            ->method('removeUpload')
            ->with(1);

        $postMock->expects($this->exactly(1))
            ->method('addUploads')
            ->with($this->callback(function ($uploadedIds) {
                return array_values($uploadedIds) == [4, 5, 7];
            }));

        $uploadsService = new UpdateUploadsInPostService();

        $uploadsService->updateUploads($postMock, $newUploadIds, $currentUploadIds);
    }
}
