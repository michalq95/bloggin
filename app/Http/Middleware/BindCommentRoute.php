<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BindCommentRoute
{

    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');
        $model = $request->route('model');

        if (!$id || !$model) {
            return new JsonResponse('Provide model and id', 422);
        }

        $allowed = ['post', 'comment', 'user'];

        if (!in_array($model, $allowed)) {
            return new JsonResponse("Invalid comment parent", 400);
        }
        $objStr = 'App\\Models\\' . ucfirst($model);
        $obj = new $objStr;
        $obj = $obj::find($id);
        if (!$obj) {
            return new JsonResponse('Commentable object not found', 404);
        }

        if ($objStr == 'App\Models\Post') {
            $ancType = $objStr;
            $ancId = $id;
        } else {
            $ancType = $obj['ancestor_type'];
            $ancId = $obj['ancestor_id'];
        }

        $request->merge([
            'model' => $model, 'commentable_type' => $objStr, 'commentable_id' => $id, 'object' => $obj,
            'ancestor_type' => $ancType, 'ancestor_id' => $ancId
        ]);
        return $next($request);
    }
}
