<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $table = 'entry';

    protected $fillable = [
        'id',
        'youtuber_id',
        'title',
        'description',
        'link',
        'published_at',
        'updated_at',
        'thumbnail',
        'views'
    ];
    public $timestamps = false;
    public $incrementing = false; 
    public $keyType = 'string';
}

?>
