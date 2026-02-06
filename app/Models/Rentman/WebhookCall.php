<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class WebhookCall extends Model
{
    protected $table = 'rm_webhook_calls';
    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * The attributes that aren't mass assignable.
     * An empty list means that all fields are mass assignable
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>|bool
     */
    // protected $fillable = [
    //     ];


}