<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="Comment",
 *      @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="post_id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="text", type="string"),
 *      @OA\Property(property="created_at", type="string", format="date-time"),
 *      @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Comment extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'text', 'post_id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
