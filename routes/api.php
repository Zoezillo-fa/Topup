<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('/tripay-webhook', [WebhookController::class, 'handle'])->name('tripay.webhook');