<?php
use App\Test;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mongo', function () {
	$users = Test::all();
	dd($users);
   // return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/shop/lists', 'ShopController@lists');
Route::get('/services', 'ShopController@services');
Route::post('/shop/create', 'ShopController@create');
Route::post('/shop/update/{id}', 'ShopController@update');
Route::delete('/shop/destroy/{id}', 'ShopController@destroy');
Route::get('/shop/search', 'ShopController@search');
Route::resource('shop', 'ShopController');

Route::get('/employee/lists', 'EmployeeController@lists');
Route::get('/employee/shops', 'EmployeeController@shops');
Route::post('/employee/create', 'EmployeeController@create');
Route::post('/employee/update/{id}', 'EmployeeController@update');
Route::delete('/employee/destroy/{id}', 'EmployeeController@destroy');
Route::resource('employee', 'EmployeeController');

Route::get('/post/lists', 'PostController@lists');
Route::post('/post/create', 'PostController@create');
Route::post('/post/update/{id}', 'PostController@update');
Route::delete('/post/destroy/{id}', 'PostController@destroy');
Route::resource('post', 'PostController');

// Route::get('/shop/search',function(){
//  $query = Input::get('query');
//  dd($query);
//  $users = User::where('name','like','%'.$query.'%')->get();
//  return response()->json($users);
// });

