<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileUpdate;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SalesController;
use App\Models\Product;

Route::get("/", function () {
    $products = Product::limit(4)->get();
    return view("landing", compact("products"));
});

Route::middleware("auth")->group(function () {
    Route::get("user/dashboard", [UserController::class, "index"])
    ->middleware(["auth", "verified"])
    ->name("userdashboard");
    Route::get("/user/dash", [UserController::class, "testDashboard"])->name("test.dash");
    Route::get("/verify", [UserController::class, "verifyAccountForm"])->name("verify.form");
    Route::post("/verify/upload", [UserController::class, "verify"])->name("verify.upload");
    Route::get("/verify/message", [UserController::class,"verifyMessage",])->name("verify.message");
    Route::get("/add-to-cart", [UserController::class, "addToCartPage"])->name("cart");
    Route::match(["get", "post"], "/add-to-cart/{productId}", [UserController::class, "addToCart"])->name("add-to-cart");
    Route::post("/checkout", [UserController::class, "prepareCheckout"])->name("checkout");
    Route::post("/create-order", [UserController::class, "createOrder"])->name("createOrder");
    Route::post("/updateQuantity/cart/{cartItemId}", [UserController::class,"updateQuantity",])->name("updateQuantity");
    Route::get("/thankyou/{orderId}", [
        UserController::class,
        "thankyou",
    ])->name("thankyou");
    Route::get("/view/order", [UserController::class, "viewOrder"])->name(
        "view.order"
    );
    Route::get("/user/profile", [UserController::class, "profile"])
        ->name("user.profile")
        ->middleware(["auth", "verified"]);
    Route::get("/order/{order}/status", [
        UserController::class,
        "fetchOrderStatus",
    ])->name("fetchOrderStatus");
    Route::post("/order/{order}/cancel", [ 
        UserController::class,
        "cancelOrder",
    ])->name("order.cancel");
    Route::get("/image/status", [
        UserController::class,
        "getImageStatus",
    ])->name("image.status");
    Route::get("/order/status", [
        UserController::class,
        "getOrderStatus",
    ])->name("order.status");
    Route::get("/notification", [UserController::class, "notification"])->name(
        "notification"
    );
    Route::get("/unread-notification", [
        UserController::class,
        "getCountNotif",
    ])->name("unread-notification");
    Route::get("/user/messages", [UserController::class, "messages"])->name(
        "user.messages"
    );
    Route::post("/profile/update/{id}", [
        ProfileController::class,
        "update",
    ])->name("profile.update");
    Route::post("/profile/update/{id}", [
        ProfileController::class,
        "update",
    ])->name("profile.update");
    Route::get("/to-receive", [UserController::class, "toReceive"])->name(
        "to-receive"
    );
    Route::get("/to-receive-unread", [
        UserController::class,
        "getToReceiveCount",
    ]);
    Route::post("/orders/{orderId}/mark-as-received", [
        UserController::class,
        "markAsReceived",
    ]);
    Route::get("/search", [SearchController::class, "search"])->name("search");
    Route::get("/uncount-add-to-cart", [
        UserController::class,
        "addToCartCount",
    ]);
    Route::get('/user/filter/products', [UserController::class, 'filter'])->name('user.productFilter');
    Route::get('/verification/test', [UserController::class, 'verificationTest'])->name('user.verificationTest');
    Route::get('/user/verification/status', [UserController::class, 'verificationStatus']);
    Route::get('/check-blocked-status', function () {
        $user = Auth::user();
        return response()->json(['is_blocked' => $user ? $user->is_blocked : false]);
    });
    Route::post('/update-notified-status', [UserController::class, 'updateNotifiedStatus']);

});
//Admin
Route::get("/dashboard", [AdminController::class, "dashboard"])
    ->middleware(["auth", "verified"])
    ->name("dashboard")
    ->middleware("admin");
Route::get("/customer", [AdminController::class, "customer"])
    ->middleware(["auth", "verified"])
    ->name("customer")  
    ->middleware("admin");
Route::get("/order", [AdminController::class, "order"])
    ->middleware(["auth", "verified"])
    ->name("admin.orders")
    ->middleware("admin");
Route::get("/analytic", [AdminController::class, "analytic"])
    ->middleware(["auth", "verified"])
    ->name("analytic");
Route::get("/message", [AdminController::class, "message"])
    ->middleware(["auth", "verified"])
    ->name("message")
    ->middleware("admin");
Route::get("/product", [AdminController::class, "product"])
    ->middleware(["auth", "verified"])
    ->name("product")
    ->middleware("admin");
Route::match(["post", "get"], "/product-add-view", [
    AdminController::class,
    "addProduct",
])
    ->middleware(["auth", "verified", "admin"])
    ->name("product-add-view");
Route::get("/admin/profile", [AdminController::class, "profile"])
    ->middleware(["auth", "verified"])
    ->name("profile");
Route::post("/profile/update/{id}", [ProfileUpdate::class, "update"])->name(
    "profile.update"
);   
Route::get("/admin/view/{userId}", [AdminController::class, "viewUserImages"])
    ->name("admin.view")
    ->middleware("admin");
Route::post("/verify/image", [AdminController::class, "verifyImage"])
    ->name("verify.image")
    ->middleware("admin");
Route::get("/admin/orders/{orderId}", [AdminController::class, "show"])
    ->name("admin.order.details")
    ->middleware("admin");
Route::post("/admin/orders/update-status", [
    AdminController::class,
    "updateStatus",
])->name("admin.orders.updateStatus");
Route::get("/admin/fetch-all-orders", [
    AdminController::class,
    "fetchOrdersAndVerification",
])->name("admin.newOrders");
Route::get("/admin/fetch-new-orders", [
    AdminController::class,
    "fetchNewOrders",
])->name("admin.fetchNewOrders");
Route::get("/admin/fetch-all-verifications", [
    AdminController::class,
    "fetchVerifications",
])->name("admin.fetchVerifications");
Route::get("/admin/fetch-new-verifications", [
    AdminController::class,
    "fetchNewVerifications",
])->name("admin.fetchNewVerifications");
Route::get("/admin/fetch-only", [
    AdminController::class,
    "justFetchOrders",
])->name("admin.fetchOnly");
Route::get("/admin/fetch-only-verifications", [
    AdminController::class,
    "justFetchVerifications",
])->name("admin.fetchVerificationOnly");
Route::get("/admin/unreadnotification", [
    AdminController::class,
    "adminGetCountNotif",
])->name("admin.getCountNotif");
Route::get("/admin/fetch-orders-and-verifications", [
    AdminController::class,
    "fetchOrdersAndVerifications",
]);
Route::get("/products/filter", [AdminController::class, "productFilter"])->name(
    "product.filter"
);
Route::get("/user-accounts", [AdminController::class, "getUsers"]);
Route::get("/users", [AdminController::class, "users"])->name("admin.users");
Route::get("/user-search", [SearchController::class, 'userSearch'])->name('user.search');
Route::get('/order/stats', [AdminController::class, 'orderStatus'])->name('admin.order.stats');
Route::get('/orders/filter', [AdminController::class, 'filter'])->name('admin.orders.filter');
Route::get('/admin/block/{id}', [AdminController::class, 'blockUser'])->name('admin.block');
Route::post('/order/{id}/send-receipt', [AdminController::class, 'sendReceipt'])->name('send-receipt');
Route::match(['get', 'post'], '/admin/report', [AdminController::class, 'reports'])->name('admin.reports');
Route::post('/admin/reports/download', [AdminController::class, 'downloadReport'])->name('admin.reports.download');
Route::get('/get-total-sales', [SalesController::class, 'getTotalSales']);


//Others
Route::post("Product-add", [ProductController::class, "addProduct"])
    ->name("products.store")
    ->middleware("admin");
Route::get("Product-update/{id}/edit", [AdminController::class, "edit"])
    ->name("update-view")
    ->middleware("admin");
Route::delete("Product-delete/{id}/detete", [
    ProductController::class,
    "destroy",
])
    ->name("product.destroy")
    ->middleware("admin");
Route::put("/products/{id}/save", [ProductController::class, "update"])
    ->name("product.update")
    ->middleware("admin");
Route::patch('/admin/product/{id}/availability', [ProductController::class, 'updateAvailability'])->name('product.update.availability');

Route::middleware(["auth"])->group(function () {
    Route::post("/send-message", [ChatController::class, "sendMessage"]);
    Route::get("/get-messages", [ChatController::class, "getMessages"]);
    Route::get("/get-users", [ChatController::class, "getUsers"]);
    Route::get("/get-admin", [ChatController::class, "getAdmin"]);
    Route::get("/unread-messages", [
        ChatController::class,
        "adminMessageCount",
    ]);
    Route::get("/user/unread-messages", [
        ChatController::class,
        "userMessageCount",
    ]);
    Route::post("/mark-messages-as-read", [
        ChatController::class,
        "markMessagesAsRead",
    ]);
    Route::post('/mark-messages-as-read-user', [ChatController::class, 'markMessagesAsReadUser']);
});

Route::middleware("auth")->group(function () {
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy"
    );
});

Route::get("/email/verify", function () {
    return view("auth.verify-email");
})
    ->middleware("auth")
    ->name("verification.notice");

require __DIR__ . "/auth.php";

