<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ChatsUserController;
use App\Http\Controllers\FCMNotificationController;
use App\Http\Controllers\Backend\AdminAgentController;
use App\Http\Controllers\Backend\AdminChatsController;
use App\Http\Controllers\Backend\AdminDemandeController;
use App\Http\Controllers\Backend\AdminCategoryController;
use App\Http\Controllers\Backend\AdminServicesController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\TranslationController;

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



Route::group([
    'middleware' =>  'api',
    'prefix' => 'v1'
], function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/loginAdmin', [AuthController::class, 'loginAdmin']);
    Route::post('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::POST('reset-password', [UserController::class, 'resetPassword']);

    
    Route::get('/services/category/all/{locale?}', [ServicesController::class, 'getServiceWithCategory']);
    Route::get('/services/all', [ServicesController::class, 'allServices']);
    Route::get('/category/all', [ServicesController::class, 'allCategory']);
    Route::get('/services/search', [ServicesController::class, 'search']);
    Route::get('/services/category/{id}', [ServicesController::class, 'serviceByCategory']);

    Route::post('/demande', [DemandeController::class, 'store']);
    Route::get('/all/demande', [DemandeController::class, 'all']);
    Route::get('/demande/all', [DemandeController::class, 'index']);
    Route::get('/demande/{id}', [DemandeController::class, 'show']);
    Route::put('/demande/{id}', [DemandeController::class, 'update']);

    Route::post('/avis', [NoteController::class, 'store']);
    Route::get('/avis/{id}', [NoteController::class, 'index']);
    Route::delete('/avis/{id}', [NoteController::class, 'destroy']);

    //profil modification

    Route::post('/profil', [UserController::class, 'update']);

    //backend
    Route::post('uploadImage', [UploadController::class, 'uploadImage']);

    // chats
    Route::get('allChat', [ChatsUserController::class, 'allChat']);
    Route::get('responseChat', [ChatsUserController::class, 'responseChat']);
    Route::post('/sendChat', [ChatsUserController::class, 'sendChat']);

    //invoices
    Route::post('invoicePaid', [FactureController::class, 'invoicePaid']);
    Route::post('invoiceReject', [FactureController::class, 'invoiceReject']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1/backend'
], function () {
    //authentification
    Route::get('/service', [AdminServicesController::class, 'index']);
    Route::post('/service', [AdminServicesController::class, 'store']);
    Route::put('/service/{id}', [AdminServicesController::class, 'update']);
    Route::delete('/service/{id}', [AdminServicesController::class, 'destroy']);
    Route::get('/category', [AdminCategoryController::class, 'index']);
    Route::get('/category/{id}', [AdminCategoryController::class, 'show']);
    Route::post('/category', [AdminCategoryController::class, 'store']);
    Route::put('/category/{id}', [AdminCategoryController::class, 'update']);
    Route::post('/category/delete/{id}', [AdminCategoryController::class, 'destroy']);
    Route::get('/agent', [AdminAgentController::class, 'index']);
    Route::post('/agent', [AdminAgentController::class, 'store']);
    Route::put('/agent/dataUpdate/{$id}', [AdminAgentController::class, 'update']);
    Route::get('/chat/read/{userId?}',[AdminChatsController::class,'readChat']);


    Route::get('/demande/all/{iduser}', [DemandeController::class, 'userDemande']);

    Route::put('/demande/status/{id}/{status}', [AdminDemandeController::class, 'update']);
    Route::put('/demande/setAgent/{id}/{agent}', [AdminDemandeController::class, 'setAgent']);

    //liste des utilisateur changeuserRole
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/changeuserstatus/{userId}', [UserController::class, 'changeuserstatus']);
    Route::put('/changeuserRole/{userId}/{status}', [UserController::class, 'changeuserRole']);

    //get all role
    Route::get('/role/all', [RoleController::class, 'index']);

    //facturation

    Route::post('/facture', [FactureController::class, 'store']);
    Route::post('/edit_facture', [FactureController::class, 'update']);

    //chat

    Route::post('/responseChat', [AdminChatsController::class, 'responseChat']);
    Route::post('/sendChat', [AdminChatsController::class, 'sendChat']);
    Route::post('/sendFile', [AdminChatsController::class, 'sendFile']);
    Route::get('/chat', [AdminChatsController::class, 'index']);


    //postServiceTranslation
    Route::get("/serviceTranslate/{id}/{type}",[TranslationController::class,'getServiceTranlation']);
    Route::put("/serviceTranslate/{id}",[TranslationController::class,'editServiceTranslation']);
    Route::post("/serviceTranslate",[TranslationController::class,'postServiceTranslation']);
    Route::delete("/serviceTranslate/{id}",[TranslationController::class,'deleteServiceTranslation']);
});


Route::post('sendNotif', [FCMNotificationController::class, 'sendNotif']);

Route::group([
    'prefix' => 'v1'

], function () {

    Route::get('allStat', [StatistiqueController::class, 'getAllStat']);
    Route::get('recentCmd', [StatistiqueController::class, 'recentCmd']);

    //authentification 

    //authentification
    Route::post('notifyDemande', [DemandeController::class, 'notify']);
});

/**************************** PayPal && tripe ROUTE *************************/

Route::group([
    'prefix' => 'v1/payment'
], function () {
    Route::get('cancel', [PaymentController::class, 'onCancel'])->name('paypal.cancel');
    Route::get('success', [PaymentController::class, 'Success'])->name('paypal.success');
    Route::post('pay', [PaymentController::class, 'pay']);
});
