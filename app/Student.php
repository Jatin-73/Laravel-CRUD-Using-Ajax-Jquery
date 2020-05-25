<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = ['fname', 'lname', 'course', 'section'];
}
