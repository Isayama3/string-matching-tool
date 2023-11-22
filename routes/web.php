<?php

use App\Http\Controllers\Web\StockholderDataExcelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('sample-data-excel-file', [StockholderDataExcelController::class, 'sampleExcelFile'])->name('sample-data-excel-file');
Route::post('import-data-excel-file', [StockholderDataExcelController::class, 'importExcelFile'])->name('import-data-excel-file');

