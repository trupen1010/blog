<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['author_id', 'title', 'sub_title', 'publish_date', 'image', 'description'];

    public function post_categories()
    {
        return $this->hasMany(PostCategory::class);
    }

    public function post_tags()
    {
        return $this->hasMany(PostTag::class);
    }

    public function author()
    {
        return $this->belongsTo(PostTag::class);
    }
}
