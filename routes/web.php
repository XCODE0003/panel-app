<?php

use Illuminate\Support\Facades\Route;
use \App\Services\Domain\UpdateDomainsStatus;

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
    return redirect('/user');
});
Route::get('/test', function () {
    $client = new UpdateDomainsStatus();
    $client->update();
    // $actions = \App\Models\Action::all();
    // $messageFunc = new \App\Services\Messages\formatMessage();
    // $settings = \App\Models\Setting::first();
    // foreach ($actions as $action) {
    //     $message = $messageFunc->format($action, $action->type);
    //     $data = [
    //         'message' => $message,
    //         'chat_id' => 8,
    //         'user_id' => $settings->user_id_botProfit,
    //     ];
    //     (new \App\Services\Messages\SendMessage())->send($data);
    // }
});

Route::get('/banned', function () {
    return "Вы в бане";
})->name('banned');
