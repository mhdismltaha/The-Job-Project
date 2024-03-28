<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompaniesPhotoController;
use App\Http\Controllers\CompanyController;

use App\Http\Controllers\PhotoController;



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
});


Route::controller(CompanyController::class)->group(function () {
    Route::post('compLogin', 'login');
    Route::post('compRegister', 'register');
    Route::post('compLogout', 'logout');
    Route::post('compRefresh', 'refresh');
    Route::get('compMe', 'me');
});

Route::controller(PhotoController::class)->group(function(){
    //for users
    Route::post('set_photo','store');
    Route::get('get_my_photo','showMine');



});

Route::controller(CompaniesPhotoController::class)->group(function(){
    //for users
    Route::post('set_comp_photo','store');
    Route::get('get_myComp_photo','showMine');



});

// Route::get('/', function () {
//     return 'Hello World';
// });

// Route::get('/greeting', function () {
//     $image =Image::load('D:\newlaravel\project1\storage\app\public\user_images\1711337701.jfif');
//     // $image = Storage::get('public/user_images/1711337701.jfif');
//     return response(['image'=>$image]);
// });
// Route::post('/set',function (Request $request){
//     $url = $request->url;
//     $image = Storage::get($url);

//      return response(base64_decode($image));
//  });





