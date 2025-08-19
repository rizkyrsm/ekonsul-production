<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    protected $fillable = ['user_id', 'is_agreed', 'agreed_at'];
}
