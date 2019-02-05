<?php
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Config::set('auth.defines', 'admin');
    Route::get('login', 'AdminAuthController@login');
    Route::post('login', 'AdminAuthController@dologin');
    Route::get('forget/password', 'AdminAuthController@forget_password');
    Route::post('forget/password', 'AdminAuthController@forget_password_post');
    Route::get('reset/password/{token}', 'AdminAuthController@reset_password');
    Route::post('reset/password/{token}', 'AdminAuthController@reset_password_post');
    Route::group(['middleware' => 'admin:admin'], function () {

        Route::resource('admin','AdminController');
        Route::delete('admin/destroy/all', 'AdminController@multi_delete');


        Route::resource('users','UsersController');
        Route::delete('users/destroy/all', 'UsersController@multi_delete');

        Route::resource('countries', 'CountriesController');
        Route::delete('countries/destroy/all', 'CountriesController@multi_delete');

        Route::resource('cities', 'CitiesController');
        Route::delete('cities/destroy/all', 'CitiesController@multi_delete');

        Route::resource('states', 'StatesController');
        Route::delete('states/destroy/all', 'StatesController@multi_delete');

        Route::resource('trademarks', 'TradeMarksController');
        Route::delete('trademarks/destroy/all', 'TradeMarksController@multi_delete');

        Route::resource('departments', 'DepartmentsController');

        Route::resource('manufacturers', 'ManufacturersController');
        Route::delete('manufacturers/destroy/all', 'ManufacturersController@multi_delete');

        Route::resource('malls', 'MallController');
        Route::delete('malls/destroy/all', 'MallController@multi_delete');

        Route::resource('colors', 'ColorController');
        Route::delete('colors/destroy/all', 'ColorController@multi_delete');

        Route::resource('products', 'productsController');
        Route::delete('products/destroy/all', 'productsController@multi_delete');
        Route::post('upload/image/{id}', 'productsController@upload_file');
        Route::post('delete/image', 'productsController@delete_file');
        Route::post('update/image/{pid}', 'productsController@update_product');
        Route::post('delete/product/image/{pid}', 'productsController@delete_main_imageProduct');
        Route::post('load/size/weight', 'productsController@prepare_size_weight');

        Route::resource('weights', 'WeightsController');
        Route::delete('weights/destroy/all', 'WeightsControllerp@multi_delete');

        Route::resource('sizes', 'SizesController');
        Route::delete('sizes/destroy/all', 'SizesController@multi_delete');

        Route::resource('shippings', 'ShippingsController');
        Route::delete('shippings/destroy/all', 'ShippingsController@multi_delete');

        Route::get('/', function () {
            return view('admin.home');
        });

        Route::get('/settings','SettingController@setting');
        Route::post('/settings','SettingController@setting_save');
        Route::any('logout', 'AdminAuthController@logout');
    });
    Route::get('lang/{lang}', function ($lang) {
        session()->has('lang')?session()->forget('lang'):'';
        $lang == 'ar'?session()->put('lang', 'ar'):session()->put('lang', 'en');
        return back();
    });
});