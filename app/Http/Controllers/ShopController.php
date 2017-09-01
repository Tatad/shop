<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use App\Service;
use App\ShopService;
use Validator;
use Illuminate\Support\Facades\Input;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        
        return view('shop.index');
    }

    public function lists(){
        //
        $shops = Shop::with('shop_services.services')->get()->toArray();
        //$shops = Shop::all();
        $result = [];
        foreach($shops as $shop){
          //$result[] = $shop;

          $shop_services = [];
          foreach($shop['shop_services'] as $service){
            $shop_services[] = $service;
          }

          $result[] = [
            '_id' => $shop['_id'],
            'name' => $shop['name'],
            'short_description' => $shop['short_description'],
            'contact' => $shop['contact'],
            'street' => $shop['street'],
            'subdivision' => $shop['subdivision'],
            'city' => $shop['city'],
            'district' => $shop['district'],
            'open_time' => $shop['open_time'],
            'close_time' => $shop['close_time'],
            'website' => $shop['website'],
            'service' => $shop_services
          ];
        }
        // echo "<pre>";
        // print_r($result);
        // die;
        return response()->json($result);
    }

    public function services(){
        $services = Service::all()->toArray();
        
        return response()->json($services);
    }

    public function search(){
      $query = Input::get('search');
      //$shop = Shop::where('street','like','%'.$query.'%')->get();

      // $shop = Shop::where('name','like','%'.$query.'%')
      // ->orWhere('street','like','%'.$query.'%')
      // ->orWhere('city','like','%'.$query.'%')
      // ->orWhere('subdivision','like','%'.$query.'%')
      // ->orWhere('district','like','%'.$query.'%')->get();

      $shop = Shop::where('name','like','%'.$query.'%')
      ->orWhere('complete_address','like','%'.$query.'%')->get();


      // $shop = Shop::all()->toArray();

      // foreach ($shop as $key => $value) {
      //   # code...
      //   //$search[] = ['search' => $value['name'].' '.$value['street'].' '.$value['subdivision'].' '.$value['city'].' '.$value['district'], 'address' => $value['street'].' '.$value['subdivision'].' '.$value['city'].' '.$value['district'], 'name' => $value['name']];

      //   $search[] = [
      //     'search' =>  $value['name'], 
      //     'address' => $value['street'].' '.$value['subdivision'].' '.$value['city'].' '.$value['district'], 'name' => $value['name']
      //   ];

      //   $search[] = [
      //     'search' =>  $value['street'].' '.$value['subdivision'].' '.$value['city'].' '.$value['district'], 
      //     'address' => $value['street'].' '.$value['subdivision'].' '.$value['city'].' '.$value['district'], 'name' => $value['name']
      //   ];
      // }

      return response()->json(count($shop) ? $shop : [0 => ['_id' => "0", 'name' => 'no results found for '.$query]]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_add_shop_rules(array $data)
    {
      
      $validator = Validator::make($data, [
        'name' => 'required|max:25',
        'contact' => 'required',
        'street' => 'required|max:25',  
      ]);

      return $validator;
    }

    public function create(Request $request)
    {
        //
        $validator = $this->admin_add_shop_rules($request->all());
        if($validator->fails())
        {
          return response()->json(array('error' => $validator->getMessageBag()->toArray()));
        }
        else
        {   
            //dd($request->all());
            $shop = new Shop([
              'name' => $request->get('name'),
              'short_description' => $request->get('short_description'),
              'contact' => $request->get('contact'),
              'street' => $request->get('street'),
              'subdivision' => $request->get('subdivision'),
              'city' => $request->get('city'),
              'district' => $request->get('district'),
              'open_time' => $request->get('open_time'),
              'close_time' => $request->get('close_time'),
              'website' => $request->get('website'),
              'complete_address' => $request->get('street').' '.$request->get('subdivision').' '.$request->get('city').' '.$request->get('district'),
            ]);
            $shop->save();  

            foreach($request->service as $service){
                $data = [
                    'shop_id' => $shop->_id,
                    'service_id' => $service
                ];
                $result = ShopService::create($data);
            }
            $response = [
                'Shop' => $shop,
                'Service' => $result
            ];

          return $response;
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $shop = Shop::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:50',
            'contact' => 'required'
        ]);
        $input = $request->all();
        $input['complete_address'] = $input->get('street').' '.$input->get('subdivision').' '.$input->get('city').' '.$input->get('district');

        $shop->fill($input)->save();

        ShopService::where('shop_id', $id)->delete();
        foreach($request->service as $service){
            $data = [
                'shop_id' => $shop->_id,
                'service_id' => $service
            ];
            $result = ShopService::create($data);
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $shop = Shop::find($id);
        $shop->delete();

        ShopService::where('shop_id', $id)->delete();
        return $shop;
        //return redirect('/shop');
    }
}
