<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="Post",
 *      @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="title", type="string"),
 *      @OA\Property(property="content", type="string"),
 *      @OA\Property(property="author", type="object",
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string")
 *      ),
 *      @OA\Property(property="comments", type="array",
 *          @OA\Items(ref="#/components/schemas/Comment")
 *      )
 * )
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'author_id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
