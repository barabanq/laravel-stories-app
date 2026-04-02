<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Story;

class Like extends Model
{
    protected $fillable = ['user_id', 'story_id'];

    public function user() {
        return $this->belongsTo('user');
    }
    public function story() {
        return $this->belongsTo('story');
    }
}
