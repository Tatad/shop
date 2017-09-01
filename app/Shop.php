<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Shop extends Eloquent
{
    //
    protected $collection = 'shops';
    protected $fillable = ['name','short_description','contact','street','subdivision','city','district','open_time','close_time','website','complete_address'];

    public function shop_services()
    {
        return $this->hasMany('App\ShopService');
    }

    // protected $hidden = [
    //     '_id'
    // ];
}
