<?php

use App\Http\Controllers\gainController;
use App\Http\Controllers\groupsController;
use App\Http\Controllers\logController;
use App\Http\Controllers\moneyController;
use App\Http\Controllers\participantController;
use Illuminate\Support\Facades\Route;


Route::get('dashbord', [logController::class, 'dashbord'])->name('dashbord');

Route::get('participants/participant/{idParticipant}/{actif}', [participantController::class, 'getParticipant'])->name('participant');
Route::post('participants/changeGroup/{idParticipant}', [groupsController::class, 'changeGroup'])->name('changeGroup');
Route::post('participants/updateParticipant/{idParticipant}', [participantController::class, 'updateParticipant'])->name('updateParticipant');
Route::get('participants/participantDelete/{idParticipant}', [participantController::class, 'participantDelete'])->name('participantDelete');
Route::get('participants/participantActiver/{idParticipant}', [participantController::class, 'participantActiver'])->name('participantActiver');

Route::get('participants/participants', [participantController::class, 'getParticipants'])->name('participants');

Route::get('participants/addParticipantForm', [participantController::class, 'addParticipantForm'])->name('addParticipantForm');
Route::post('participants/addParticipant', [participantController::class, 'addParticipant'])->name('addParticipant');

Route::get('participants/jouerForm', [moneyController::class, 'getJouerForm'])->name('jouerForm');
Route::post('participants/debitAll', [moneyController::class, 'debitAll'])->name('debitAll');



Route::get('participants/searchParticipant', [participantController::class, 'searchParticipant'])->name('searchParticipant');
Route::get('participants/rgpd', [participantController::class, 'rgpd'])->name('rgpd');

Route::post('addMoney/{idParticipant}', [moneyController::class, 'addMoney'])->name('addMoney');
Route::post('debitMoney/{idParticipant}', [moneyController::class, 'debitMoney'])->name('debitMoney');
Route::post('retriveMoney/{idParticipant}', [moneyController::class, 'retriveMoney'])->name('retriveMoney');

Route::post('addGain', [gainController::class, 'addGain'])->name('addGain');
Route::get('getGainHistory', [gainController::class, 'getGainHistory'])->name('getGainHistory');

Route::post('addGroup', [groupsController::class, 'addGroup'])->name('addGroup');
Route::post('participantGroup', [groupsController::class, 'participantGroup'])->name('participantGroup');
