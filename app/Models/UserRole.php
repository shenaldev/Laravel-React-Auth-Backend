<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'role_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
