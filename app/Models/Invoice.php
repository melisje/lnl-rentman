<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'tbl_invoices';
    protected $primaryKey = 'id';
    public $incrementing = false;

    const CREATED_AT = '_created';
    const UPDATED_AT = '_updated';
}
