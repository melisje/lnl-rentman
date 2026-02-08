<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ApiToken extends Model
{
    /** @use HasFactory<\Database\Factories\Rentman\ApiTokenFactory> */
    use HasFactory;

    protected $table = 'rm_api_tokens';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $fillable = ['account', 'url', 'token'];

    protected $casts = [
        "token" => "encrypted",
    ];

}
