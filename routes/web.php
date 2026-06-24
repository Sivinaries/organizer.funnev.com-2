<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActController;
use App\Http\Controllers\HotController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TransactionController;


//AUTH CONTROLLER
Route::get('/', [PagesController::class, 'signin'])->name('signin');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/signup', [PagesController::class, 'signup'])->name('signup');
Route::post('/register', [AuthController::class, 'register'])->name('register');

//GOOGLE CONTROLLER
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('oauth');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('callback');

Route::fallback(function () {
    return view('errors.404');
});

Route::middleware('auth:sanctum')->group(function () {

    //PAGES CONTROLLER
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [PagesController::class, 'profil'])->name('profil');
    Route::get('/search', [PagesController::class, 'search'])->name('search');

    //USER CONTROLLER 
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::delete('/deluser/{id}', [UserController::class, 'destroy'])->name('deluser');

    //ORGANIZER CONTROLLER 
    Route::get('/organizers', [OrganizerController::class, 'index'])->name('organizers');
    Route::delete('/delorganizer/{id}', [OrganizerController::class, 'destroy'])->name('delorganizer');

    //TRANSACTION CONTROLLER
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/transaction/{id}/{no_order}', [TransactionController::class, 'show'])->name('showtransaction');

    //SCAN CONTROLLER
    Route::get('/scanner', [ScanController::class, 'scanner'])->name('scanner');
    Route::get('/scan/{no_ticket}', [ScanController::class, 'scan'])->name('getticket');

    //EVENT CONTROLLER
    Route::get('/category', [KategoriController::class, 'index'])->name('category');
    Route::post('/postcategory', [KategoriController::class, 'store'])->name('postcategory');
    Route::put('/updatecategory/{id}', [KategoriController::class, 'update'])->name('updatecategory');
    Route::delete('/delcategory/{id}', [KategoriController::class, 'destroy'])->name('delcategory');

    //EVENT CONTROLLER
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::post('/postevent', [EventController::class, 'store'])->name('postevent');
    Route::get('/showevent/{id}/{event}', [EventController::class, 'show'])->name('showevent');
    Route::delete('/delevent/{id}', [EventController::class, 'destroy'])->name('delevent');

    //HOT CONTROLLER
    Route::get('/hots', [HotController::class, 'index'])->name('hots');
    Route::post('/posthot', [HotController::class, 'store'])->name('posthot');
    Route::delete('/delhot/{id}', [HotController::class, 'destroy'])->name('delhot');

    //PRICING CONTROLLER
    Route::get('/pricings', [PricingController::class, 'index'])->name('pricings');
    Route::post('/postpricing', [PricingController::class, 'store'])->name('postpricing');
    Route::put('/updatepricing/{id}', [PricingController::class, 'update'])->name('updatepricing');
    Route::delete('/delpricing/{id}', [PricingController::class, 'destroy'])->name('delpricing');

    //TCIKET CONTROLLER
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets');
    Route::post('/postticket', [TicketController::class, 'store'])->name('postticket');
    Route::put('/updateticket/{id}', [TicketController::class, 'update'])->name('updateticket');
    Route::delete('/delticket/{id}', [TicketController::class, 'destroy'])->name('delticket');

    //SUBMISSION CONTROLLER
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions');
    Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('showsubmission');
    Route::delete('/delsubmission/{id}', [SubmissionController::class, 'destroy'])->name('delsubmission');

    //APPROVAL CONTROLLER
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals');
    Route::get('/showapproval/{id}/{name}', [ApprovalController::class, 'show'])->name('showapproval');
    Route::post('/postapproval/{id}', [ApprovalController::class, 'store'])->name('postapproval');
    Route::delete('/delapproval/{id}', [ApprovalController::class, 'destroy'])->name('delapproval');

    //ATTENDANCES CONTROLLER
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances');
    Route::post('/postattendance', [AttendanceController::class, 'store'])->name('postattendance');

    //WITHDRAW CONTROLLER
    Route::get('/withdraws', [WithdrawController::class, 'index'])->name('withdraws');
    Route::post('/postwithdraw', [WithdrawController::class, 'store'])->name('postwithdraw');
    Route::get('/showwithdraw/{id}/{no_rek}', [WithdrawController::class, 'show'])->name('showwithdraw');
    Route::patch('/approve/{id}/{no_rek}', [WithdrawController::class, 'approve'])->name('approvewithdraw');

    Route::get('/activities', [ActController::class, 'index'])->name('activities');

    //LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
