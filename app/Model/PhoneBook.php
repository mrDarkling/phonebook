<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PhoneBook extends Model
{
    protected $table = 'phone_book';
    protected $fillable = [
      'number',
      'name',
    ];
}
