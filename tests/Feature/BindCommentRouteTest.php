<?php

namespace Tests\Feature;

use App\Http\Middleware\BindCommentRoute;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Mockery;
use PHPUnit\Framework\TestCase;

class BindCommentRouteTest extends TestCase
{

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testValidRequest()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('route')->with('id')->andReturn(1);
        $request->shouldReceive('route')->with('model')->andReturn('post');

        $post = Mockery::mock('alias:App\Models\Post');
        $post->shouldReceive('find')->with(1)->andReturn(new \stdClass());

        // $request->shouldReceive('merge')->with(['commentable_type' => 'App\\Models\\Post', 'commentable_id' => 1]);
        $object = new \stdClass();
        $request->shouldReceive('merge')->with(
            [
                'commentable_type' => 'App\\Models\\Post',
                'commentable_id' => 1,
                'model' => 'post',
                'object' => $object
            ]
        );

        $closure = function ($request) {
            return new Response();
        };

        $middleware = new BindCommentRoute();
        $response = $middleware->handle($request, $closure);

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testInvalidModel()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('route')->with('id')->andReturn(1);
        $request->shouldReceive('route')->with('model')->andReturn('invalid_model');

        $closure = function ($request) {
            return new Response();
        };

        $middleware = new BindCommentRoute();
        $response = $middleware->handle($request, $closure);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testObjectNotFound()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('route')->with('id')->andReturn(1);
        $request->shouldReceive('route')->with('model')->andReturn('post');

        $post = Mockery::mock('alias:App\Models\Post');
        $post->shouldReceive('find')->with(1)->andReturn(null);

        $closure = function ($request) {
            return new Response();
        };

        $middleware = new BindCommentRoute();
        $response = $middleware->handle($request, $closure);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
