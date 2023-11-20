<?php

use App\Services\UploadProcessing\ImageProcessingService;
use PHPUnit\Framework\TestCase;
use App\Models\Image;
use App\Models\Uploads;
use Mockery;

class ImageProcessingServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateMiniature()
    {
        $imageMock = Mockery::mock("alias:" . Image::class);
        $imageMock->shouldReceive('width')->andReturn(600);
        $imageMock->shouldReceive('height')->andReturn(300);
        $imageMock->shouldNotReceive('resize')->once()->with(300, 300, Mockery::type('Closure'));
        $imageMock->extension = 'png';
        $imageMock->shouldReceive('save')->once();

        $dataMock = Mockery::mock('Uploads');
        $dataMock->shouldReceive('image')->andReturn(Mockery::self());
        $dataMock->shouldReceive('save')->once();

        $imageProcessingService = new ImageProcessingService($imageMock, $dataMock);

        $result = $imageProcessingService->createMiniature();

        $this->assertStringContainsString('newimages/', $result);
        $this->assertStringEndsWith('.png', $result);
    }
    public function testCreateAlreadySmallMiniature()
    {
        $imageMock = Mockery::mock("alias:" . Image::class);
        $imageMock->shouldReceive('width')->andReturn(300);
        $imageMock->shouldReceive('height')->andReturn(300);
        $imageMock->shouldNotReceive('resize');
        $imageMock->extension = 'jpg';
        $imageMock->shouldReceive('save')->once();

        $dataMock = Mockery::mock('Uploads');
        $dataMock->shouldReceive('image')->andReturn(Mockery::self());
        $dataMock->shouldReceive('save')->once();

        $imageProcessingService = new ImageProcessingService($imageMock, $dataMock);

        $result = $imageProcessingService->createMiniature();

        $this->assertStringContainsString('newimages/', $result);
        $this->assertStringEndsWith('.jpg', $result);
    }
}
