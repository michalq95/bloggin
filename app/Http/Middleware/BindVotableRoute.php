<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BindVotableRoute
{

    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');
        $model = $request->route('model');

        if (!$id || !$model) {
            return new JsonResponse('Provide model and id', 422);
        }

        $allowed = ['post', 'comment'];

        if (!in_array($model, $allowed)) {
            return new JsonResponse("Invalid vote parent", 400);
        }
        $objStr = 'App\\Models\\' . ucfirst($model);
        $obj = new $objStr;
        $obj = $obj::find($id);
        if (!$obj) {
            return new JsonResponse('Votable object not found', 404);
        }

        $request->merge([
            'model' => $model, 'voteable_type' => $objStr, 'voteable_id' => $id, 'object' => $obj,
        ]);
        return $next($request);
    }
}
