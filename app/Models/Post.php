<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'images',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
