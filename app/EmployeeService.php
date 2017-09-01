<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class EmployeeService extends Eloquent
{
    //
    protected $collection = 'employee_services';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '_id',
        'service_id',
        'employee_id',
        'updated_at',
    ];

    protected $fillable = ['employee_id','service_id'];

    public function services()
    {
        return $this->belongsTo('App\Service', 'service_id', '_id');
    }
}
