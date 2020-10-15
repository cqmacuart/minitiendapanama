<?php

use Illuminate\Support\Facades\Route;

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
// offline routes
Route::get('/offline', function () {
    return view('vendor/laravelpwa/offline');
});

// offline routes
Route::post('/sendingmail/nuevo', "SendMailController@nuevoPedido");
Route::post('/sendingmail/remember', "SendMailController@remember");

// categories routes
Route::get('/categories/info', 'CategoriesController@info');
Route::post('/categories/saveFile', 'CategoriesController@saveFileImage');
Route::get('/categories/page', 'CategoriesController@pageIndex');
Route::resource('/categories', 'CategoriesController');

//products routes
Route::get('/products/info', 'ProductsController@info');
Route::post('/products/saveFile', 'ProductsController@saveFileImage');
Route::get('/products/filteredByCategory/{id}', 'ProductsController@filteredByCategory');
Route::get('/products/filteredBySearch/{filter}', 'ProductsController@filteredBySearch');
Route::get('/products/filtered/{producto?}/{categoria?}/{estado?}', 'ProductsController@filtered');
Route::get('/products/page', 'ProductsController@pageIndex');
Route::resource('/products', 'ProductsController');

// orders routes
Route::get('/orders/info', 'OrdersController@info');
Route::get('/orders/filtered/{filter}', 'OrdersController@filter');
Route::get('/admin/customerByOrder/{id}', 'OrdersController@customerByOrder');
Route::get('/miorden/{token}', 'OrdersController@pedido');
Route::resource('/orders', 'OrdersController');

// types routes
Route::resource('/types', 'TypesController');

// status routes
Route::resource('/status', 'StatusController');

// OrderStatus routes
Route::resource('/orderStates', 'OrderStatesController');

// //Transacciones Routes
Route::resource('/admin/pagos', 'TransactionController');
Route::get('/admin/pagos/filtered/{filter}', 'TransactionController@filter');

//Settings Routes
Route::post('/admin/settings/saveFile', 'SettingController@saveFileImage');
Route::post('/admin/settings/saveResponsiveFile', 'SettingController@saveResponsive');
Route::resource('/admin/settings', 'SettingController');


// Colors routes
Route::get('/colors/count', 'ColorsController@count');
Route::resource('/colors', 'ColorsController');

// Users routes
Route::get('/admin/users/remember', 'UsersController@remember');
Route::resource('/admin/users', 'UsersController');


// Cart routes
Route::get('/cart/step', 'CartController@index')->name('cart');
Route::get('/addToCart/{id}', 'CartController@add')->name('cart.add');
Route::get('/subsToCart/{id}', 'CartController@subs')->name('cart.subs');
Route::get('/cartCount', 'CartController@cartCount')->name('cart.count');
Route::get('/cartCount/{id}', 'CartController@countProductInCart');
Route::get('/cart/removeItem/{id}', 'CartController@removeItem')->name('cart.remove');
Route::get('/cart/amount', 'CartController@total')->name('cart.total');
Route::get('/cart/isincart/{id}', 'CartController@isInCart')->name('cart.incart');

Route::post('/authenticate/login', 'Auth\LoginController@login');
Route::post('/authenticate/logout', 'Auth\LoginController@logout');



// PASARELAS DE PAGO
// EPAYCO
Route::match(["get", "post"], "/epayco/apiresponse/{opt?}", 'EpaycoController@postReceive');
Route::resource('/admin/epayco', 'EpaycoController');

Route::get('/admin/{option?}', function () {
    return view('admin');
})->name('basepath')
    ->where('option', '.*');

Route::get('/{option?}', function () {
    return view('app');
})->name('basepath')
    ->where('option', '.*');
