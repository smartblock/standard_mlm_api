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

    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('register', [\App\Http\Controllers\Member\Auth\RegisterController::class, 'save']);
        $router->post('login', [\App\Http\Controllers\Member\Auth\LoginController::class, 'login']);
        $router->post('forgotPassword', [\App\Http\Controllers\Member\Auth\ForgotPasswordController::class, 'save']);
        $router->post('password/reset', [\App\Http\Controllers\Member\Auth\ForgotPasswordController::class, 'postResetPassword']);

        $router->group(['prefix' => 'otp'], function () use ($router) {
            $router->post('', [\App\Http\Controllers\Member\OtpController::class, 'save']);
            $router->get('{email}/{code}', [\App\Http\Controllers\Member\OtpController::class, 'validateOTP']);
        });
    });


    $router->get('sponsor/{username}', [\App\Http\Controllers\Member\Auth\RegisterController::class, 'validateSponsor']);

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('group/{type}', [\App\Http\Controllers\Member\ProductController::class, 'index']);
    });

    $router->group(['middleware' => ['auth:sanctum', 'abilities:role-member']], function () use ($router) {
        $router->group(['prefix' => 'profile'], function () use ($router) {
            $router->get('', [\App\Http\Controllers\Member\MemberController::class, 'getProfile']);
            $router->post('', [\App\Http\Controllers\Member\MemberController::class, 'updateProfile']);
            $router->post('password', [\App\Http\Controllers\Member\MemberController::class, 'changePassword']);
        });

        $router->group(['prefix' => 'addresses'], function () use ($router) {
            $router->get('', [\App\Http\Controllers\Member\MemberController::class, 'getAddresses']);
        });
    });
});
