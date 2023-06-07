<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetTokens extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';
    protected $fillable = ['email', 'token'];
}
