<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['prefix' => 'member'], function () use ($router) {
    $router->get('languages', [\App\Http\Controllers\LanguageController::class, 'index']);
    $router->get('countries', [\App\Http\Controllers\Member\CountryController::class, 'index']);

    $router->group(['prefix' => 'register'], function () use ($router) {
        $router->post('', [\App\Http\Controllers\Member\Auth\RegisterController::class, 'save'])->name('member.register.post');

        $router->group(['prefix' => 'otp'], function () use ($router) {
            $router->post('', [\App\Http\Controllers\Member\OtpController::class, 'save'])->name('member.register.otp.post');
            $router->get('{email}/{code}', [\App\Http\Controllers\Member\OtpController::class, 'validateOTP'])->name('member.register.otp.get');
        });
    });

    $router->post('login', [\App\Http\Controllers\Member\Auth\LoginController::class, 'login'])->name('member.login.post');

    $router->get('sponsor/{username}', [\App\Http\Controllers\Member\Auth\RegisterController::class, 'validateSponsor'])->name('member.sponsor.get');

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('group/{type}', [\App\Http\Controllers\Member\ProductController::class, 'index']);
    });

    $router->group(['middleware' => ['auth:sanctum', 'abilities:role-member']], function () use ($router) {

    });
});
