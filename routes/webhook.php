<?php

use Illuminate\Support\Facades\Route;

Route::post(config('kuraimibank.webhook_url'), \AlSaloul\KuraimibankPayment\Http\Controllers\CheckIfUserExists::class);
