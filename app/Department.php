<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "hr_departments";
    protected $fillable = [
        'department_name', 'department_desc', 'department_info'
    ];
}
