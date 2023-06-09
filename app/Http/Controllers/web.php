<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FetchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\FetchAdminController;
use App\Http\Controllers\UpdateAdminController;
use App\Http\Controllers\AgingPullOutController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\RegisterController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return Inertia::render('Login');
});

Route::get('/pulloutrequests', function () {
    return Inertia::render('PullOutRequests');
})->middleware(['auth', 'verified'])->name('pulloutrequests');

Route::get('/dashboardcopy', function () {
    return Inertia::render('Dashboardcopy');
})->middleware(['auth', 'verified'])->name('dashboardcopy');

Route::get('/pulloutgenerator', function () {
    return Inertia::render('PullOutGenerator');
})->middleware(['auth', 'verified'])->name('pulloutgenerator');

Route::get('/pulloutgenerator2', function () {
    return Inertia::render('PullOutGenerator2');
})->middleware(['auth', 'verified'])->name('pulloutgenerator2');

Route::get('/statistic', function () {
    return Inertia::render('Statistic');
})->middleware(['auth', 'verified'])->name('statistic');

Route::get('/processcopy', function () {
    return Inertia::render('Processcopy');
})->middleware(['auth', 'verified'])->name('processcopy');


Route::get('/copy', function () {
    return Inertia::render('Copy');
})->middleware(['auth', 'verified'])->name('copy');

Route::get('/admin', function () {
    return Inertia::render('Admin');
})->middleware(['auth', 'verified'])->name('admin');

Route::get('/users', function () {
    return Inertia::render('Users');
})->middleware(['auth', 'verified'])->name('users');

Route::get('/admin-boxmaintenance', function () {
    return Inertia::render('Box');
})->middleware(['auth', 'verified'])->name('admin-boxmaintenance');

Route::get('/admin-usermaintenance', function () {
    return Inertia::render('Users');
})->middleware(['auth', 'verified'])->name('admin-usermaintenance');

Route::get('/admin-branchmaintenance', function () {
    return Inertia::render('Branch');
})->middleware(['auth', 'verified'])->name('admin-branchmaintenance');

Route::get('/admin-brandmaintenance', function () {
    return Inertia::render('Brand');
})->middleware(['auth', 'verified'])->name('admin-brandmaintenance');

Route::get('/admin-loghistory', function () {
    return Inertia::render('LogHistory');
})->middleware(['auth', 'verified'])->name('admin-loghistory');

Route::get('/admin-personnelmaintenance', function () {
    return Inertia::render('Personnel');
})->middleware(['auth', 'verified'])->name('admin-personnelmaintenance');

Route::get('/admin-reasonmaintenance', function () {
    return Inertia::render('Reason');
})->middleware(['auth', 'verified'])->name('admin-reasonmaintenance');

Route::get('/admin-itemmaintenance', function () {
    return Inertia::render('Items');
})->middleware(['auth', 'verified'])->name('admin-itemmaintenance');

Route::get('/metrobankform', function () {
    return Inertia::render('metrobankForm');
})->middleware(['auth', 'verified'])->name('metrobankform');

Route::get('/otpform', function () {
    return Inertia::render('otpForm');
})->name('otpform');


// Route::get('/copy', function () {
//     return Inertia::render('Copy');
//     // $pdf = PDF::loadView('copy', $data);
//     // return $pdf->download('invoice.pdf');
// })->middleware(['auth', 'verified'])->name('copy');
Route::get('/checkAccStatus', [PostController::class, 'checkAccStatus']);
Route::get('/sendVerificationCode', [RegisterController::class, 'sendVerificationCode']);
Route::get('/confirmCode', [RegisterController::class, 'confirmCode']);

Route::middleware('auth')->group(function () {
    Route::get('/users',[FetchController::class, 'users']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/fetchCompany', [FetchController::class, 'fetchCompany']);
    Route::get('/fetchSameItemNBFI', [FetchController::class, 'fetchSameItemNBFI']);
    // Route::get('/data', [FetchController::class, 'fetchData']);
    Route::get('/fetchChain', [FetchController::class, 'fetchChain']);
    Route::get('/fetchChainCode', [FetchController::class, 'fetchChainCode']);
    Route::get('/fetchBranch', [FetchController::class, 'fetchBranch']);
    Route::get('/fetchChainName', [FetchController::class, 'fetchChainName']);
    Route::get('/fetchBrands', [FetchController::class, 'fetchBrands']);
    Route::get('/fetchCategory', [FetchController::class, 'fetchCategory']);
    Route::get('/fetchItems', [FetchController::class, 'fetchItems']);
    Route::get('/compareItemCode', [FetchController::class, 'compareItemCode']);
    Route::get('/fetchItemsNBFI', [FetchController::class, 'fetchItemsNBFI']);
    Route::post('/submit', [PostController::class, 'postPullOutRequest']);
    Route::post('/addNewBranch', [PostController::class, 'addNewBranch']);
    Route::post('/addNewBrand', [PostController::class, 'addNewBrand']);
    Route::post('/addNewDriver', [PostController::class, 'addNewDriver']);
    Route::post('/addNewReason', [PostController::class, 'addNewReason']);

    Route::get('/fetchPullOutReq', [FetchController::class, 'fetchPullOutRequest']);
    Route::get('/fetchReasonLabel', [FetchController::class, 'getReasons']);
    Route::get('/fetchPullOutRequestUnprocessed', [FetchController::class, 'fetchPullOutRequestUnprocessed']);
    Route::get('/fetchPullOutRequestUnprocessed2', [FetchController::class, 'fetchPullOutRequestUnprocessed2']);
    Route::get('/fetchPullOutRequestItem', [FetchController::class, 'fetchPullOutRequestItem']);
    Route::get('/fetchPullOutRequestApproved', [FetchController::class, 'fetchPullOutRequestApproved']);
    Route::get('/fetchPullOutRequestDenied', [FetchController::class, 'fetchPullOutRequestDenied']);
    Route::get('/fetchItemsRequest', [FetchController::class, 'fetchItemsRequest']);
    Route::get('/fetchAllItemsRequest', [FetchController::class, 'fetchAllItemsRequest']);
    Route::get('/getLastID', [FetchController::class, 'fetchLastID']);
    Route::get('/getPromoName', [FetchController::class, 'getPromoName']);
    Route::get('/getBranchStatus', [FetchController::class, 'getBranchStatus']);
    Route::get('/fetchUserRequestDraft', [FetchController::class, 'fetchUserRequestDraft']);
    Route::get('/fetchUserRequestTransactionList', [FetchController::class, 'fetchUserRequestTransactionList']);

    Route::get('/generatePDF', [PostController::class, 'generatePDF']);
    Route::get('/generatePDF1', [PostController::class, 'generatePDF1']);
    // Route::get('/generatePDF', [PostController::class, 'generateReport']);
    Route::get('/generateReport', [ReportController::class, 'generateReport']);

    Route::post('/updateItemQuantity', [UpdateController::class, 'updateItemQuantity']);
    Route::post('/updateStatus', [UpdateController::class, 'updateStatus']);
    Route::post('/updateBranchStatus', [UpdateController::class, 'updateBranchStatus']);

    Route::get('/fetchLastIDBranch', [FetchController::class, 'fetchLastIDBranch']);
    Route::post('/savePullOutBranchRequest', [PostController::class, 'savePullOutBranchRequest']);
    Route::post('/savePullOutItemRequest', [PostController::class, 'savePullOutItemRequest']);

    Route::post('/sendDeniedBranch', [PostController::class, 'sendDeniedBranch']);

    Route::get('/fetchUsersMaintenance', [FetchAdminController::class, 'fetchUsers']);
    Route::get('/fetchEPCBrandsMaintenance', [FetchAdminController::class, 'fetchEPCBrandsMaintenance']);
    Route::get('/fetchEPCItemsMaintenance', [FetchAdminController::class, 'epcItems']);
    Route::get('/fetchPullOutBranchMaintenance', [FetchAdminController::class, 'fetchPullOutBranch']);
    Route::get('/fetchPullOutItemsMaintenance', [FetchAdminController::class, 'fetchPullOutItemsMaintenance']);
    Route::get('/fetchBoxLabelMaintenance', [FetchAdminController::class, 'fetchBoxLabel']);
    Route::get('/fetchReasonLabelMaintenance', [FetchAdminController::class, 'fetchReasonLabel']);
    Route::get('/fetchBranchMaintenance', [FetchAdminController::class, 'fetchBranchMaintenance']);
    Route::get('/fetchBrandMaintenance', [FetchAdminController::class, 'fetchBrandMaintenance']);
    Route::get('/fetchDriverMaintenance', [FetchAdminController::class, 'fetchDriverMaintenance']);
    Route::get('/fetchTransactionLogs', [FetchAdminController::class, 'fetchTransactionLogs']);

    Route::post('/updateUserAcc', [UpdateAdminController::class, 'updateUserAcc']);
    Route::post('/updateBranch', [UpdateAdminController::class, 'updateBranch']);
    Route::post('/updateBrand', [UpdateAdminController::class, 'updateBrand']);
    Route::post('/updateDriver', [UpdateAdminController::class, 'updateDriver']);
    Route::post('/updateReason', [UpdateAdminController::class, 'updateReason']);

    //Importing csv file
    Route::post('/branchImport', [ImportController::class, 'branchImport']);
    Route::post('/itemsImport', [ImportController::class, 'itemsImport']);

    Route::post('/agingController', [AgingPullOutController::class, 'agingController']);

});



require __DIR__.'/auth.php';
