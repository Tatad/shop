<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Employee extends Eloquent
{
    //
    protected $collection = 'employees';
    protected $fillable = ['first_name','middle_name','last_name','birth_date','experience_years','shop_id'];

    public function employee_services()
    {
        //return $this->belongsTo('App\EmployeeService', '_id', 'employee_id');
        return $this->hasMany('App\EmployeeService');
    }

    public function shop()
    {
        return $this->belongsTo('App\Shop', 'shop_id', '_id');
    }
}
