<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserWithRelationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,

            'posts' => PostResource::collection($this->whenLoaded($this->posts)),
            'comments' => CommentResource::collection($this->whenLoaded($this->comments)),
        ];
    }
}
