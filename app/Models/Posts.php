<?php

namespace App\Models;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $fillable = [
        'content',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes() {
        return $this->belongsToMany(User::class,'likes', 'post_id', 'user_id');
    }

    public function hashedid() {
        $hashedid = new Hashids();
        return $hashedid->encode($this->id);
    }
}
