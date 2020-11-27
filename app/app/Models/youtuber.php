<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Youtuber extends Model
{
    protected $table = 'youtuber';
    protected $fillable = [
        'id',
        'name',
        'url',
        'create_at'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType='string';
}
 
?>
