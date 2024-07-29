<?php

use App\Http\Controllers\gainController;
use App\Http\Controllers\groupsController;
use App\Http\Controllers\logController;
use App\Http\Controllers\moneyController;
use App\Http\Controllers\participantController;
use Illuminate\Support\Facades\Route;


// Route::get('', [logController::class, 'logReg'])->name('logReg');
// Route::post('reg', [logController::class, 'register_action'])->name('register.action');
// Route::post('login', [logController::class, 'login_action'])->name('login.action');
// Route::get('logout', [logController::class, 'logout'])->name('logout');


Route::get('participants', [participantController::class, 'home'])->name('home');
Route::get('participants/participant/{idParticipant}', [participantController::class, 'participant'])->name('participant');
Route::post('participants/addParticipant', [participantController::class, 'addParticipant'])->name('addParticipant');
Route::get('participants/participantDelete/{idParticipant}', [participantController::class, 'participantDelete'])->name('participantDelete');
Route::post('participants/updateParticipant/{idParticipant}', [participantController::class, 'updateParticipant'])->name('updateParticipant');
Route::post('participants/changeGroup/{idParticipant}', [participantController::class, 'changeGroup'])->name('changeGroup');
Route::get('participants/searchParticipant', [participantController::class, 'searchParticipant'])->name('searchParticipant');
Route::get('participants/rgpd', [participantController::class, 'rgpd'])->name('rgpd');

Route::post('addMoney/{idParticipant}', [moneyController::class, 'addMoney'])->name('addMoney');
Route::post('debitMoney/{idParticipant}', [moneyController::class, 'debitMoney'])->name('debitMoney');
Route::post('retriveMoney/{idParticipant}', [moneyController::class, 'retriveMoney'])->name('retriveMoney');
Route::post('debitAll', [moneyController::class, 'debitAll'])->name('debitAll');

Route::post('addGain', [gainController::class, 'addGain'])->name('addGain');
Route::get('getGainHistory', [gainController::class, 'getGainHistory'])->name('getGainHistory');

Route::post('addGroup', [groupsController::class, 'addGroup'])->name('addGroup');
Route::post('participantGroup', [groupsController::class, 'participantGroup'])->name('participantGroup');
