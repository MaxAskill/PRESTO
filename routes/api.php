<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FetchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\FetchAdminController;
use App\Http\Controllers\UpdateAdminController;
use App\Http\Controllers\AgingPullOutController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\FetchDashboardController;
use Barryvdh\DomPDF\Facade\Pdf;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'Logout']);
});

Route::post('/login', [AuthController::class, 'Login']);
Route::post('/register', [AuthController::class, 'Register']);
Route::get('/sendVerificationCode', [RegisterController::class, 'sendVerificationCode']);
Route::get('/sendForgotVerificationCode', [RegisterController::class, 'sendForgotVerificationCode']);
Route::get('/confirmCode', [RegisterController::class, 'confirmCode']);
Route::post('/updatePasswordUser', [RegisterController::class, 'updatePasswordUser']);

Route::get('/users',[FetchController::class, 'users']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/fetchCompany', [FetchController::class, 'fetchCompany']);
    Route::get('/fetchBranchStatus', [FetchController::class, 'fetchBranchStatus']);
    Route::get('/fetchCompanyByRA', [FetchController::class, 'fetchCompanyByRA']);
    Route::get('/fetchCompanyByUser', [FetchController::class, 'fetchCompanyByUser']);
    Route::get('/fetchSameItem', [FetchController::class, 'fetchSameItem']);
    // Route::get('/data', [FetchController::class, 'fetchData']);
    Route::get('/fetchChain', [FetchController::class, 'fetchChain']);
    Route::get('/fetchChainByUser', [FetchController::class, 'fetchChainCodeByUser']);
    Route::get('/fetchSameItems', [FetchController::class, 'fetchSameItems']);
    Route::get('/fetchChainCode', [FetchController::class, 'fetchChainCode']);
    Route::get('/fetchBranch', [FetchController::class, 'fetchBranch']);
    Route::get('/fetchChainName', [FetchController::class, 'fetchChainName']);
    Route::get('/fetchChainNameByUser', [FetchController::class, 'fetchChainNameByUser']);
    Route::get('/fetchBrands', [FetchController::class, 'fetchBrands']);
    Route::get('/fetchCategory', [FetchController::class, 'fetchCategory']);
    Route::get('/fetchItems', [FetchController::class, 'fetchItems']);
    Route::get('/fetchItemsNBFIExcel', [FetchController::class, 'fetchItemsNBFIExcel']);
    Route::get('/fetchItemsEPCExcel', [FetchController::class, 'fetchItemsEPCExcel']);
    Route::get('/compareItemCode', [FetchController::class, 'compareItemCode']);
    Route::get('/fetchItemsNBFI', [FetchController::class, 'fetchItemsNBFI']);
    Route::get('/fetchPromoBranchInfo', [FetchController::class, 'fetchPromoBranchInfo']);
    Route::post('/submit', [PostController::class, 'postPullOutRequest']);
    Route::post('/addNewBranch', [PostController::class, 'addNewBranch']);
    Route::post('/addNewBrand', [PostController::class, 'addNewBrand']);
    Route::post('/addNewDriver', [PostController::class, 'addNewDriver']);
    Route::post('/addNewReason', [PostController::class, 'addNewReason']);
    Route::post('/deleteDraft', [PostController::class, 'deleteDraft']);
    Route::post('/deleteUserBranch', [PostController::class, 'deleteUserBranch']);
    Route::post('/postUserBranch', [PostController::class, 'postUserBranch']);
    Route::post('/postRemarks', [PostController::class, 'postRemarks']);
    Route::post('/upload', [PostController::class, 'upload']);
    Route::post('/postPromoUserBranch', [PostController::class, 'postPromoUserBranch']);
    Route::post('/removeTempBranch', [UpdateController::class, 'removeTempBranch']);
    Route::post('/updateTempBranch', [UpdateController::class, 'updateTempBranch']);
    Route::post('/updatePermBranch', [UpdateController::class, 'updatePermBranch']);
    Route::post('/postDatesLetter', [PostController::class, 'postDatesLetter']);
    Route::post('/logsDeleteItemEdit', [PostController::class, 'logsDeleteItemEdit']);

    Route::get('/fetchPullOutReq', [FetchController::class, 'fetchPullOutRequest']);
    Route::get('/fetchReasonLabel', [FetchController::class, 'getReasons']);
    Route::get('/fetchPullOutRequestUnprocessed', [FetchController::class, 'fetchPullOutRequestUnprocessed']);
    Route::get('/fetchPullOutRequestEndorsement', [FetchController::class, 'fetchPullOutRequestEndorsement']);
    Route::get('/fetchPullOutRequestItem', [FetchController::class, 'fetchPullOutRequestItem']);
    Route::get('/fetchPullOutRequestApproved', [FetchController::class, 'fetchPullOutRequestApproved']);
    Route::get('/fetchPullOutRequestDenied', [FetchController::class, 'fetchPullOutRequestDenied']);
    Route::get('/fetchItemsRequest', [FetchController::class, 'fetchItemsRequest']);
    Route::get('/fetchAllItemsRequest', [FetchController::class, 'fetchAllItemsRequest']);
    Route::get('/fetchAllItemsRequestExport', [FetchController::class, 'fetchAllItemsRequestExport']);
    Route::get('/getLastID', [FetchController::class, 'fetchLastID']);
    Route::get('/fetchGenerateLetter', [FetchController::class, 'fetchGenerateLetter']);
    Route::get('/getPromoName', [FetchController::class, 'getPromoName']);
    Route::get('/getBranchStatus', [FetchController::class, 'getBranchStatus']);
    Route::get('/fetchUserRequestDraft', [FetchController::class, 'fetchUserRequestDraft']);
    Route::get('/fetchCompanyDraft', [FetchController::class, 'fetchCompanyDraft']);
    Route::get('/fetchUserRequestTransactionList', [FetchController::class, 'fetchUserRequestTransactionList']);
    Route::get('/fetchEditDraftBranch', [FetchController::class, 'fetchEditDraftBranch']);
    Route::get('/fetchEditDraftItem', [FetchController::class, 'fetchEditDraftItem']);
    Route::get('/fetchNewAmount', [FetchController::class, 'fetchNewAmount']);
    Route::get('/usersMaintenance', [FetchController::class, 'usersMaintenance']);
    Route::get('/usersMaintenanceViewDetails', [FetchController::class, 'usersMaintenanceViewDetails']);
    Route::get('/usersMaintenanceRequestBranch', [FetchController::class, 'usersMaintenanceRequestBranch']);
    Route::get('/usersProfile', [FetchController::class, 'usersProfile']);
    Route::get('/fetchImageBranchDoc', [FetchController::class, 'fetchImageBranchDoc']);
    Route::get('/fetchItemsBarcode', [FetchController::class, 'fetchItemsBarcode']);
    Route::get('/fetchPullOutLetterDates', [FetchController::class, 'fetchPullOutLetterDates']);

    Route::get('/generatePDF', [PostController::class, 'generatePDF']);
    Route::get('/generatePDF1', [PostController::class, 'generatePDF1']);
    // Route::get('/generatePDF', [PostController::class, 'generateReport']);
    Route::get('/generateReport', [ReportController::class, 'generateReport']);

    Route::post('/updateItemQuantity', [UpdateController::class, 'updateItemQuantity']);
    Route::post('/updateStatus', [UpdateController::class, 'updateStatus']);
    Route::post('/updateBranchStatus', [UpdateController::class, 'updateBranchStatus']);
    Route::post('/updateBranchStatusApprover', [UpdateController::class, 'updateBranchStatusApprover']);

    Route::get('/fetchLastIDBranch', [FetchController::class, 'fetchLastIDBranch']);
    Route::post('/savePullOutBranchRequest', [PostController::class, 'savePullOutBranchRequest']);
    Route::post('/savePullOutItemRequest', [PostController::class, 'savePullOutItemRequest']);
    Route::post('/updatePullOutBranchRequest', [UpdateController::class, 'updatePullOutBranchRequest']);
    Route::post('/updatePullOutItemRequest', [UpdateController::class, 'updatePullOutItemRequest']);
    Route::post('/updateUserBranch', [UpdateController::class, 'updateUserBranch']);
    Route::post('/updateUserBranchByRequest', [UpdateController::class, 'updateUserBranchByRequest']);

    Route::post('/sendDeniedBranch', [PostController::class, 'sendDeniedBranch']);
    Route::post('/deleteImage', [PostController::class, 'deleteImage']);

    Route::get('/fetchUsersMaintenance', [FetchAdminController::class, 'fetchUsers']);
    Route::get('/fetchEPCBrandsMaintenance', [FetchAdminController::class, 'fetchEPCBrandsMaintenance']);
    Route::get('/fetchItemsMaintenance', [FetchAdminController::class, 'epcItems']);
    Route::get('/fetchPullOutBranchMaintenance', [FetchAdminController::class, 'fetchPullOutBranch']);
    Route::get('/fetchPullOutItemsMaintenance', [FetchAdminController::class, 'fetchPullOutItemsMaintenance']);
    Route::get('/fetchBoxLabelMaintenance', [FetchAdminController::class, 'fetchBoxLabel']);
    Route::get('/fetchReasonLabelMaintenance', [FetchAdminController::class, 'fetchReasonLabel']);
    Route::get('/fetchBranchMaintenance', [FetchAdminController::class, 'fetchBranchMaintenance']);
    Route::get('/fetchBrandMaintenance', [FetchAdminController::class, 'fetchBrandMaintenance']);
    Route::get('/fetchDriverMaintenance', [FetchAdminController::class, 'fetchDriverMaintenance']);
    Route::get('/fetchTransactionLogs', [FetchAdminController::class, 'fetchTransactionLogs']);
    Route::get('/fetchChainCodeAdmin', [FetchAdminController::class, 'fetchChainCode']);
    Route::get('/fetchUserRequestTransactionListAdmin', [FetchAdminController::class, 'fetchUserRequestTransactionList']);

    Route::post('/updateUserAcc', [UpdateAdminController::class, 'updateUserAcc']);
    Route::post('/updateBranch', [UpdateAdminController::class, 'updateBranch']);
    Route::post('/updateBrand', [UpdateAdminController::class, 'updateBrand']);
    Route::post('/updateDriver', [UpdateAdminController::class, 'updateDriver']);
    Route::post('/updateReason', [UpdateAdminController::class, 'updateReason']);

    Route::get('/fetchDashboardToday', [FetchDashboardController::class, 'fetchDashboardToday']);
    Route::get('/fetchDashboardUnprocessed', [FetchDashboardController::class, 'fetchDasboardUnprocessed']);
    Route::get('/fetchDashboardApproved', [FetchDashboardController::class, 'fetchDashboardApproved']);
    Route::get('/fetchDashboardDenied', [FetchDashboardController::class, 'fetchDashboardDenied']);

    //Importing csv file
    Route::post('/branchImport', [ImportController::class, 'branchImport']);
    Route::post('/itemsImport', [ImportController::class, 'itemsImport']);

    Route::post('/agingController', [AgingPullOutController::class, 'agingController']);
    Route::post('/SLACountNBFI', [AgingPullOutController::class, 'SLACountNBFI']);

Route::middleware('auth')->group(function () {


});
