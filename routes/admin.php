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

$router->group(['prefix' => 'admin'], function () use ($router) {
    $router->post('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login');

    $router->group(['middleware' => ['auth:sanctum', 'abilities:role-admin']], function () use ($router) {
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.list');
            $router->post('', [\App\Http\Controllers\Admin\AdminController::class, 'save'])->name('admin.post');
            $router->get('{id}', [\App\Http\Controllers\Admin\AdminController::class, 'edit'])->name('admin.get');
            $router->put('{id}', [\App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admin.update');
            $router->delete('{id}', [\App\Http\Controllers\Admin\AdminController::class, 'delete'])->name('admin.delete');
        });

        $router->group(['prefix' => 'members'], function () use ($router) {
            $router->get('', [\App\Http\Controllers\Admin\MemberController::class, 'index'])->name('member.list');
            $router->post('sponsor', [\App\Http\Controllers\Admin\SponsorController::class, 'save'])->name('member.sponsor.post');
        });

        $router->group(['prefix' => 'roles'], function () use ($router) {
            $router->get('group', [\App\Http\Controllers\Admin\RoleController::class, 'tree'])->name('role.all');
            $router->get('', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('role.list');
            $router->post('', [\App\Http\Controllers\Admin\RoleController::class, 'save'])->name('role.post');
            $router->get('{code}', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('role.get');
            $router->put('{code}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('role.update');
            $router->delete('{id}', [\App\Http\Controllers\Admin\RoleController::class, 'delete'])->name('role.delete');
        });

        $router->group(['prefix' => 'languages'], function () use ($router) {
//            $router->get('all', [\App\Http\Controllers\Admin\SysLanguageController::class, 'all'])->name('language.all');
            $router->get('', [\App\Http\Controllers\Admin\SysLanguageController::class, 'all'])->name('language.list');
            $router->post('', [\App\Http\Controllers\Admin\SysLanguageController::class, 'save'])->name('language.post');
            $router->get('{id}', [\App\Http\Controllers\Admin\SysLanguageController::class, 'edit'])->name('language.get');
            $router->put('{id}', [\App\Http\Controllers\Admin\SysLanguageController::class, 'update'])->name('language.update');
            $router->delete('{id}', [\App\Http\Controllers\Admin\SysLanguageController::class, 'delete'])->name('language.delete');
        });

        $router->group(['prefix' => 'general/settings'], function () use ($router) {
            $router->get('', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'index'])->name('setting.list');
            $router->post('', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'save'])->name('setting.post');
            $router->get('{type}/{code}', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'edit'])->name('setting.get');
            $router->put('{id}', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'update'])->name('setting.update');
        });

        $router->get('wallets', [\App\Http\Controllers\Admin\Wallet\WalletController::class, 'all'])->name('wallet.all');

        $router->group(['prefix' => 'wallet'], function () use ($router) {
            $router->post('adjustments', [\App\Http\Controllers\Admin\Wallet\AdjustmentController::class, 'save'])->name('wallet.adjustment.post');
            $router->get('statements', [\App\Http\Controllers\Admin\Wallet\WalletController::class, 'index'])->name('wallet.statement.get');
            $router->get('balances', [\App\Http\Controllers\Admin\Wallet\WalletController::class, 'getBalances'])->name('wallet.balances.get');

            $router->group(['prefix' => 'transfers'], function () use ($router) {
                $router->get('', [\App\Http\Controllers\Admin\Wallet\TransferController::class, 'index'])->name('wallet.transfer.get');
                $router->post('', [\App\Http\Controllers\Admin\Wallet\TransferController::class, 'save'])->name('wallet.transfer.post');
            });
        });

        $router->group(['prefix' => 'products'], function () use ($router) {
            $router->post('', [\App\Http\Controllers\Admin\Product\ProductController::class, 'save'])->name('product.post');
            $router->post('mix-and-match', [\App\Http\Controllers\Admin\Product\ProductMixMatchController::class, 'save'])->name('product.mix-match.post');
        });

        $router->group(['prefix' => 'product/packages'], function () use ($router) {
            $router->post('', [\App\Http\Controllers\Admin\Product\PackageController::class, 'save'])->name('product.package.post');
        });

        $router->group(['prefix' => 'product/categories'], function () use ($router) {
            $router->get('tree', [\App\Http\Controllers\Admin\Product\CategoryController::class, 'all'])->name('product.category.get');
            $router->post('', [\App\Http\Controllers\Admin\Product\CategoryController::class, 'save'])->name('product.category.post');
            $router->get('{id}', [\App\Http\Controllers\Admin\Product\CategoryController::class, 'edit'])->name('product.category.get');
            $router->put('{id}', [\App\Http\Controllers\Admin\Product\CategoryController::class, 'update'])->name('product.category.put');
            $router->delete('{id}', [\App\Http\Controllers\Admin\Product\CategoryController::class, 'delete'])->name('product.category.delete');
        });

        $router->group(['prefix' => 'stock'], function () use ($router) {
            $router->get('locations', [\App\Http\Controllers\Admin\Stock\StockLocationController::class, 'all']);

            $router->group(['prefix' => 'suppliers'], function () use ($router) {
                $router->get('', [\App\Http\Controllers\Admin\Stock\SupplierController::class, 'index'])->name('stock.supplier.get');
                $router->get('{id}', [\App\Http\Controllers\Admin\Stock\SupplierController::class, 'edit'])->name('stock.supplier.edit');
                $router->put('{id}', [\App\Http\Controllers\Admin\Stock\SupplierController::class, 'update'])->name('stock.supplier.update');
                $router->post('', [\App\Http\Controllers\Admin\Stock\SupplierController::class, 'save'])->name('stock.supplier.post');
                $router->delete('{id}', [\App\Http\Controllers\Admin\Stock\SupplierController::class, 'delete'])->name('stock.supplier.delete');
            });

            $router->group(['prefix' => 'receives'], function () use ($router) {
                $router->get('', [\App\Http\Controllers\Admin\Stock\GoodReceiveController::class, 'index'])->name('stock.receive.get');
                $router->post('', [\App\Http\Controllers\Admin\Stock\GoodReceiveController::class, 'save'])->name('stock.receive.post');
            });

            $router->post('adjustments', [\App\Http\Controllers\Admin\Stock\GoodAdjustmentController::class, 'save'])->name('stock.adjustment.post');
        });

        $router->group(['prefix' => 'announcements'], function () use ($router) {
            $router->get('', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcement.get');
            $router->get('{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'edit'])->name('announcement.edit');
            $router->post('', [\App\Http\Controllers\Admin\AnnouncementController::class, 'save'])->name('announcement.post');
        });
    });
});
