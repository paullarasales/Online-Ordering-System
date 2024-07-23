

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileUpdate;
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function (){
    return view('landing');
});

Route::middleware('auth')->group(function (){
    Route::get('user/dashboard', [UserController::class, 'index'])->name('userdashboard');
    Route::get('/verify', [UserController::class, 'verifyAccountForm'])->name('verify.form');
    Route::post('/verify/upload', [UserController::class, 'verify'])->name('verify.upload');
    Route::get('/verify/message', [UserController::class, 'verifyMessage'])->name('verify.message');
    Route::get('/add-to-cart', [UserController::class, 'addToCartPage'])->name('cart');
    Route::match(['get', 'post'], '/add-to-cart/{productId}', [UserController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/checkout', [UserController::class, 'prepareCheckout'])->name('checkout');
    Route::post('/create-order', [UserController::class, 'createOrder'])->name('createOrder');
    Route::post('/updateQuantity/cart/{cartItemId}', [UserController::class, 'updateQuantity'])->name('updateQuantity');
    Route::get('/thankyou/{orderId}', [UserController::class, 'thankyou'])->name('thankyou');
    Route::get('/view/order', [UserController::class, 'viewOrder'])->name('view.order');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile')->middleware(['auth', 'verified']);
    Route::get('/order/{order}/status', [UserController::class, 'fetchOrderStatus'])->name('fetchOrderStatus');
    Route::post('/order/{order}/cancel', [UserController::class, 'cancelOrder'])->name('order.cancel');
});

Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard')->middleware('admin');
Route::get('/customer', [AdminController::class, 'customer'])->middleware(['auth', 'verified'])->name('customer')->middleware('admin');
Route::get('/order', [AdminController::class, 'order'])->middleware(['auth', 'verified'])->name('order')->middleware('admin');
Route::get('/analytic', [AdminController::class, 'analytic'])->middleware(['auth', 'verified'])->name('analytic');
Route::get('/message', [AdminController::class, 'message'])->middleware(['auth', 'verified'])->name('message')->middleware('admin');
Route::get('/product', [AdminController::class, 'product'])->middleware(['auth', 'verified'])->name('product')->middleware('admin');
Route::match(['post', 'get'],'/product-add-view', [AdminController::class, 'addProduct'])->middleware(['auth', 'verified', 'admin'])->name('product-add-view');
Route::get('/admin/profile', [AdminController::class, 'profile'])->middleware(['auth', 'verified'])->name('profile');
Route::post('/profile/update/{id}',  [ProfileUpdate::class, 'update'])->name('profile.update')->middleware('admin');
Route::get('/admin/view/{userId}', [AdminController::class, 'viewUserImages'])->name('admin.view')->middleware('admin');
Route::post('/verify/image', [AdminController::class, 'verifyImage'])->name('verify.image')->middleware('admin');
Route::get('/admin/orders/{orderId}', [AdminController::class, 'show'])->name('admin.order.details')->middleware('admin');
Route::post('/admin/orders/update-status', [AdminController::class, 'updateStatus'])->name('admin.orders.updateStatus');
Route::get('/admin/fetch-all-orders', [AdminController::class, 'fetchOrdersAndVerification'])->name('admin.newOrders');
Route::get('/admin/fetch-new-orders', [AdminController::class, 'fetchNewOrders'])->name('admin.fetchNewOrders');
Route::get('/admin/fetch-all-verifications', [AdminController::class, 'fetchVerifications'])->name('admin.fetchVerifications');
Route::get('/admin/fetch-new-verifications', [AdminController::class, 'fetchNewVerifications'])->name('admin.fetchNewVerifications');
Route::get('/admin/fetch-only', [AdminController::class, 'justFetchOrders'])->name('admin.fetchOnly');
Route::get('/admin/fetch-only-verifications', [AdminController::class, 'justFetchVerifications'])->name('admin.fetchVerificationOnly');


Route::post('Product-add', [ProductController::class, 'addProduct'])->name('products.store')->middleware('admin');
Route::get('Product-update/{id}/edit', [AdminController::class, 'edit'])->name('update-view')->middleware('admin');
Route::delete('Product-delete/{id}/detete', [ProductController::class, 'destroy'])->name('product.destroy')->middleware('admin');
Route::put('/products/{id}/save', [ProductController::class, 'update'])->name('product.update')->middleware('admin');

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/check-new-messages', [AdminController::class, 'checkNewMessages'])->name('check.new.messages')->middleware('admin');
    Route::get('/admin/messages', [AdminController::class, 'getMessages'])->name('admin.messages.fetch')->middleware('admin');
    Route::get('/sse/messages', [AdminController::class, 'streamMessages'])->name('sse.messages')->middleware('admin');
    Route::post('/chat/respond', [ChatController::class, 'respondToCustomer'])->name('chat.respond')->middleware('admin');
});

Route::middleware('auth')->group(function () {
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');



require __DIR__.'/auth.php';
