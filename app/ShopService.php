<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ShopService extends Eloquent
{
    //
    protected $collection = 'shop_services';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '_id',
        'service_id',
        'shop_id',
        'updated_at',
    ];

    protected $fillable = ['shop_id','service_id'];

    public function services()
    {
        return $this->belongsTo('App\Service', 'service_id', '_id');
    }
}
