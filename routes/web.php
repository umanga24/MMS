<?php

use App\Http\Controllers\admin\AdminLoginController;

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSellController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\DailyExpenseController;
use App\Http\Controllers\admin\DailyIncomeController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SummaryController;
use App\Http\Controllers\FrontController;
use App\Models\Brand;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Catch_;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [FrontController::class, 'index'])->name('front.home');

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Routes accessible by both role 1 and role 2
        Route::group(['middleware' => 'role:1,3'], function () {
            // Category Routes
            Route::get('/players', [CategoryController::class, 'index'])->name('players.index');
            Route::get('/players-half', [CategoryController::class, 'half'])->name('players.half');

            Route::get('/players/create', [CategoryController::class, 'create'])->name('players.create');
            Route::post('/players', [CategoryController::class, 'store'])->name('players.store');



            // Product Routes

        });

        // Routes only accessible by role 2
        Route::group(['middleware' => 'role:1,3,2'], function () {
            // Category Edit/Delete Routes
            Route::get('/players/{player}/edit', [CategoryController::class, 'edit'])->name('players.edit');
            Route::put('/players/{player}', [CategoryController::class, 'update'])->name('players.update');

            Route::post('/players/{id}/update-additional', [CategoryController::class, 'updateAdditional'])->name('players.updateAdditional');
            Route::get('/players/list-additional', [CategoryController::class, 'listAdditional'])->name('player.listAdditional');

            Route::get('/players/{id}/edit-additional/{additionalId}', [CategoryController::class, 'editAdditional'])->name('players.editAdditional');
            Route::put('/players/{id}/update-additionallist/{additionalId}', [CategoryController::class, 'updateAdditionallist'])->name('players.updateAdditionalList');


            Route::patch('/players/{id}/hide', [CategoryController::class, 'hide'])->name('players.hide');


            // Daily Expenses Route
            Route::get('/dailyexpenses', [DailyExpenseController::class, 'index'])->name('dailyexpenses.index');
            Route::get('/dailyexpenses/create', [DailyExpenseController::class, 'create'])->name('dailyexpenses.create');
            Route::post('/dailyexpenses', [DailyExpenseController::class, 'store'])->name('dailyexpenses.store');
            Route::get('/dailyexpenses/{dailyexpense}/edit', [DailyExpenseController::class, 'edit'])->name('dailyexpenses.edit');
            Route::put('/dailyexpenses/{dailyexpense}', [DailyExpenseController::class, 'update'])->name('dailyexpenses.update');


            //Daily Income Route
            Route::get('/dailyincomes', [DailyIncomeController::class, 'index'])->name('dailyincomes.index');
            Route::get('/dailyincomes/create', [DailyIncomeController::class, 'create'])->name('dailyincomes.create');
            Route::post('/dailyincomes', [DailyIncomeController::class, 'store'])->name('dailyincomes.store');
            Route::get('/dailyincomes/{dailyincome}/edit', [DailyIncomeController::class, 'edit'])->name('dailyincomes.edit');
            Route::put('/dailyincomes/{dailyincome}', [DailyIncomeController::class, 'update'])->name('dailyincomes.update');





            // Product Delete Routes
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');

            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');


            // Sells Product Route

            Route::get('/productsells', [ProductSellController::class, 'index'])->name('productsells.index');
            Route::get('/productsells/create', [ProductSellController::class, 'create'])->name('productsells.create');
            Route::post('/productsells', [ProductSellController::class, 'store'])->name('productsells.store');
            Route::get('/productsells/{productsell}/edit', [ProductSellController::class, 'edit'])->name('productsells.edit');
            Route::put('/productsells/{productsell}', [ProductSellController::class, 'update'])->name('productsells.update');
        });

        Route::group(['middleware' => 'role:1,2'], function () {

            Route::get('/summary', [SummaryController::class, 'index'])->name('summary.index');
        });


        // Reports and Summaries Routes
        Route::group(['middleware' => 'role:1,2,3'], function () {
            Route::get('/reports', [CategoryController::class, 'list'])->name('report.list');
            Route::get('/summaries', [CategoryController::class, 'summary'])->name('summary.list');
        });

        Route::group(['middleware' => 'role:1,2,3'], function () {

            Route::get('/products/report', [ProductController::class, 'list'])->name('products.report');
        });



        //Role 1
        Route::group(['middleware' => 'role:1'], function () {
            Route::delete('/players/{player}', [CategoryController::class, 'destroy'])->name('players.delete');

            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');

            Route::delete('/productsells/{productsell}', [ProductSellController::class, 'destroy'])->name('productsells.delete');

            Route::delete('/dailyexpenses/{dailyexpense}', [DailyExpenseController::class, 'destroy'])->name('dailyexpenses.delete');
            Route::delete('/dailyincomes/{dailyincome}', [DailyIncomeController::class, 'destroy'])->name('dailyincomes.delete');

            // Brands Routes
            Route::get('/brands', [BrandsController::class, 'index'])->name('brands.index');
            Route::get('/brands/create', [BrandsController::class, 'create'])->name('brands.create');
            Route::post('/brands', [BrandsController::class, 'store'])->name('brands.store');

            // Brands Edit/Delete Routes
            Route::get('/brands/{brand}/edit', [BrandsController::class, 'edit'])->name('brands.edit');
            Route::put('/brands/{brand}', [BrandsController::class, 'update'])->name('brands.update');
            Route::delete('/brands/{brand}', [BrandsController::class, 'destroy'])->name('brands.delete');
        });
    });
});
