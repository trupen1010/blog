<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'author_id' => $this->author_id,
            /* 'author' => AuthorResource::collection($this->author), */
            'publish_date' => $this->publish_date,
            'sequence' => $this->sequence,
            'is_featured' => $this->is_featured,
            'image' => $this->image,
            'imageUrl' => url('/images/Post/'),
            'description' => $this->description,
            'created_at' => $this->created_at->format('d-m-Y'),
            /* 'post_categories' => PostCategoryResource::collection($this->post_category),
            'post_tags' => PostTagResource::collection($this->post_tag), */
        ];
    }
}
