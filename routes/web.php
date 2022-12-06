<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Models\Reservation;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//ADMIN
Route::middleware( ['admin'])->group(function () {
    //books
    Route::get('/api/books/{id}', [BookController::class, 'show']);
    Route::post('/api/books', [BookController::class, 'store']);
    Route::put('/api/books/{id}', [BookController::class, 'update']);
    Route::delete('/api/books/{id}', [BookController::class, 'destroy']);
    //copies
    Route::apiResource('/api/copies', CopyController::class);
    //queries
    Route::get('/api/book_copies/{title}', [BookController::class, 'bookCopies']);
    //view - copy
    Route::get('/copy/new', [CopyController::class, 'newView']);
    Route::get('/copy/edit/{id}', [CopyController::class, 'editView']);
    Route::get('/copy/list', [CopyController::class, 'listView']); 
});
//konyvtaros
Route::middleware( ['librarian'])->group(function () {
    //books
    
    //copies

    //queries

    //view - copy
    Route::get('/api/elojegyzesdb/', [ReservationController::class, 'elojegyzesDB']);

});

//SIMPLE USER
Route::middleware(['auth.basic'])->group(function () {

    
    //user   
    Route::apiResource('/api/users', UserController::class);
    Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
    //queries
    Route::get('/api/hardcovered', [CopyController::class, 'hardCopies']);
    Route::get('/api/year_copies/{year}/{author}/{title}', [CopyController::class, 'yearCopies']);
    Route::get('/api/year_copies_storage/{year}/{author}/{title}', [CopyController::class, 'yearCopiesStorage']);
    Route::get('/api/in_storage', [LendingController::class, 'inStorage']);
    Route::get('/api/lendingDataDB/{copy_id}', [CopyController::class, 'lendingDataDB']);
    Route::get('/api/lendingDataWITH/{copy_id}', [CopyController::class, 'lendingDataWITH']);
    //user lendings
    Route::get('/api/user_lendings', [LendingController::class, 'userLendingsList']);
    Route::get('/api/user_lendings_unique_count', [LendingController::class, 'userLendingsCount']);
    Route::get('/api/user_lendings_count', [LendingController::class, 'userLendingsCountWithoutDistinct']);
    Route::get('/api/kolcsonzes/{db}', [LendingController::class, 'kolcsParam']);
    //Route::get('/api/elojegyzesdb/', [ReservationController::class, 'elojegyzesDB']);
});
//csak a tesztelés miatt van "kint"
Route::patch('/api/users/password/{id}', [UserController::class, 'updatePassword']);
Route::apiResource('/api/copies', CopyController::class);
Route::get('/api/lendings', [LendingController::class, 'index']); 
Route::get('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'show']);
Route::put('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']);
Route::patch('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'update']);
Route::post('/api/lendings', [LendingController::class, 'store']);
Route::delete('/api/lendings/{user_id}/{copy_id}/{start}', [LendingController::class, 'destroy']);


Route::get('/api/szerzokentCsopABC', [BookController::class, 'szerzokentCsopABC']);
Route::get('/api/morethanone', [BookController::class, 'moreThan1']);
Route::get('/api/startswithb', [BookController::class, 'startsWithB']);

Route::get('/api/startswithany/{text}', [BookController::class, 'startsWithBArgument']);

Route::get('/api/selejt', [CopyController::class, 'deleteSelejt']);
Route::get('/api/older/{day}', [ReservationController::class, 'older']);

Route::get('/api/reserved/{konyv_id}', [LendingController::class, 'reserved']);
Route::get('send-mail', [MailController::class, 'index']);

Route::get('file_upload', [FileController::class, 'index']);
Route::post('file_upload', [FileController::class, 'store'])->name('file.store');
Route::get('/api/resusers', [ReservationController::class, 'reservUsers']);
Route::post('/api/resdelete', [ReservationController::class, 'deleteOldReservs']);
Route::patch('/api/elrejtes', [ReservationController::class, 'elrejt']);



require __DIR__.'/auth.php';
