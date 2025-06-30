<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\{OrderController, PointController, ReviewController, ProductController, RajaOngkirController, TransactionController,IndianLocationController};
use App\Http\Controllers\ShippingCostController;
use App\Http\Controllers\ShiprocketController;
use Illuminate\Support\Facades\DB;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/home', function () {
        return view('partials.home.home_admin');
    })->name('admin.home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::controller(ProfileController::class)->group(function () {
        Route::get("/profile/my_profile", "myProfile")->name('profile.myProfile');
        Route::get("/profile/edit_profile", "editProfileGet");
        Route::post("/profile/edit_profile/{user:id}", "editProfilePost");
        Route::get("/profile/change_password", "changePasswordGet");
        Route::post("/profile/change_password", "changePasswordPost");
    });
});

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

require __DIR__.'/auth.php';

Route::get('/auth/login', [AuthController::class, 'loginGet'])->name('login');
Route::post('/auth/login', [AuthController::class, 'loginPost']);
Route::get('/auth/register', [AuthController::class, 'registrationGet'])->name('register');
Route::post('/auth/register', [AuthController::class, 'registrationPost']);
Route::post('/auth/logout', [AuthController::class, 'logoutPost'])->name('logout');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/chart/sales_chart', [ChartController::class, 'salesChart'])->middleware('auth');
Route::get('/chart/profits_chart', [ChartController::class, 'profitsChart'])->middleware('auth');

// main
Route::middleware(['auth'])->group(function () {
    // Home
    Route::controller(HomeController::class)->group(function () {
        Route::get("/home", "index");
        Route::get("/home/customers", "customers");
    });

    // profile
   

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get("/product", "index");
        Route::get("/product/data/{id}", "getProductData");

        // admin only
        Route::get("/product/add_product", "addProductGet")->can("add_product", App\Models\Product::class);
        Route::post("/product/add_product", "addProductPost")->can("add_product", App\Models\Product::class);
        Route::get("/product/edit_product/{product:id}", "editProductGet")->can("edit_product", App\Models\Product::class);
        Route::post("/product/edit_product/{product:id}", "editProductPost")->can("edit_product", App\Models\Product::class);
    });

    // Order
    Route::controller(OrderController::class)->group(function () {
        Route::get("/order/order_data", "orderData");
        Route::get("/order/order_history", "orderHistory");
        Route::get("/order/order_data/{status_id}", "orderDataFilter");
        Route::get("/order/data/{order}", "getOrderData")->can("my_real_order", "order");
        Route::get("/order/getProof/{order}", "getProofOrder")->can("my_real_order", "order");


        // customer only
        Route::get("/order/make_order/{product:id}", "makeOrderGet")->can("create_order", App\Models\Order::class);
        Route::post("/order/make_order/{product:id}", "makeOrderPost")->can("create_order", App\Models\Order::class);
        Route::get("/order/edit_order/{order}", "editOrderGet")->can("edit_order", "order");
        Route::post("/order/edit_order/{order}", "editOrderPost")->can("edit_order", "order");
        Route::get("/order/delete_proof/{order}", "deleteProof")->can("delete_proof", "order");
        Route::post("/order/cancel_order/{order}", "cancelOrder")->can("cancel_order", "order");
        Route::post("/order/upload_proof/{order}", "uploadProof")->can("upload_proof", "order");

        // admin only
        Route::post("/order/reject_order/{order}/{product}", "rejectOrder")->can("reject_order", App\Models\Order::class);
        Route::post("/order/end_order/{order}/{product}", "endOrder")->can("end_order", App\Models\Order::class);
        Route::post("/order/approve_order/{order}/{product}", "approveOrder")->can("approve_order", App\Models\Order::class);
    });

    // Indian Location (replacing RajaOngkir)
    Route::controller(IndianLocationController::class)->group(function () {
        Route::get("/shipping/province", "states");
        Route::get("/shipping/city/{state_id}", "cities");
    });

    // Shipping cost (custom)
    Route::controller(ShippingCostController::class)->group(function () {
        Route::get("/shipping/cost/{origin}/{destination}/{quantity}/{courier}", "cost");
    });

    // review
    Route::controller(ReviewController::class)->group(function () {
        Route::get("/review/product/{product}", "productReview");
        Route::get("/review/data/{review}", "getDataReview");
        Route::post("/review/add_review/", "addReview");
        Route::post("/review/edit_review/{review}", "editReview")->can("edit_review", "review");
        Route::post("/review/delete_review/{review}", "deleteReview")->can("delete_review", "review");
    });

    // transaction
    Route::controller(TransactionController::class)->group(function () {
        Route::get("/transaction", "index")->can("is_admin");
        Route::get("/transaction/add_outcome", "addOutcomeGet")->can("is_admin");
        Route::post("/transaction/add_outcome", "addOutcomePost")->can("is_admin");
        Route::get("/transaction/edit_outcome/{transaction}", "editOutcomeGet")->can("is_admin");
        Route::post("/transaction/edit_outcome/{transaction}", "editOutcomePost")->can("is_admin");
    });

    // point
    Route::controller(PointController::class)->group(function () {
        Route::get("/point/user_point", "user_point")->can("user_point", App\Models\User::class);
        Route::post("/point/convert_point", "convert_point")->can("convert_point", App\Models\User::class);
    });


    // chart
    Route::middleware(['can:is_admin'])->group(function () {
        // sales chart
        Route::get("/chart/sales_chart", function () {
            $oneWeekAgo = DB::select(DB::raw('SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 DAY), "%Y-%m-%d") AS date'))[0]->date;

            $now = date('Y-m-d', time());

            $array_result = [
                "one_week_ago" => $oneWeekAgo,
                "now" => $now,
            ];

            //disable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $array_result["data"] = DB::table("orders")
                ->selectSub("count(*)", "sales_total")
                ->selectSub("DATE_FORMAT(orders.updated_at, '%d')", "day")
                ->selectSub("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')", "date")
                ->where("is_done", 1)
                ->whereBetween(DB::raw("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')"), ["$oneWeekAgo", $now])
                ->groupByRaw("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')")
                ->get();
            //re-enable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

            echo json_encode($array_result);
        });
        // profits chart
        Route::get("/chart/profits_chart", function () {
            $six_month_ago = DB::select(DB::raw('SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), "%Y-%m") AS month'))[0]->month;
            $now = date('Y-m', time());
            $array_result = [
                "six_month_ago" => $six_month_ago,
                "now" => $now,
            ];

            //disable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $array_result["data"] = DB::table("transactions")
                ->selectSub("SUM(income) - SUM(outcome)", "profits")
                ->selectSub("DATE_FORMAT(transactions.created_at, '%Y-%m')", "date")
                ->whereBetween(DB::raw("DATE_FORMAT(transactions.created_at, '%Y-%m')"), ["$six_month_ago", $now])
                ->groupByRaw("DATE_FORMAT(transactions.created_at, '%Y-%m')")
                ->get();
            //re-enable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

            echo json_encode($array_result);
        });
    });


});
