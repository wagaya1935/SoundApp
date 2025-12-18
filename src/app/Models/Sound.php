<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;

class Sound extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
