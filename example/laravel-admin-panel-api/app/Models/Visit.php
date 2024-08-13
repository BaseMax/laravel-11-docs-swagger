<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        "ip_address", "user_agent", "visited_at"
    ];

    public function visitablle(): MorphTo
    {
        return $this->morphTo("visitable");
    }
}
