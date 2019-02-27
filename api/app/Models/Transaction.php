<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  date
 */
class Transaction extends Model
{
    protected $table = 'transaction';

    public $timestamps = false;

    protected $dateFormat = 'U';
}
