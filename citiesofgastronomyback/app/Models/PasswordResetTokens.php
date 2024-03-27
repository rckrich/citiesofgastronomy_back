<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PasswordResetTokens extends Model
{
    use HasFactory;
    protected $table = "password_reset_tokens";


}
