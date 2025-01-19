<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ListPostsRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Blog Posts API",
 *     version="1.0.0"
 * )
 */
class PostController
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="List blog posts",
     *     description="Retrieve a list of blog posts with optional filters.",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         description="Filter posts by author ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Search posts by title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Post")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="per_page", type="integer")
     *             )
     *         )
     *     )
     *)
     */
    public function index(ListPostsRequest $request): JsonResponse
    {
        DB::enableQueryLog();

        $query = Post::query()
            ->with(['author', 'comments'])
            ->when($request->author_id, function ($query, $authorId) {
                $query->where('author_id', $authorId);
            })
            ->when($request->title, function ($query, $title) {
                $query->where('title', 'like', "%{$title}%");
            });

        $posts = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => $posts->items(),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'query' => DB::getQueryLog(),
            ],
        ]);
    }
}
