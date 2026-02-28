<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/postman/download', function () {
  $file = public_path('TASK MANAGER.postman_collection.json');

  if (!file_exists($file)) {
    $file = base_path('TASK MANAGER.postman_collection.json');
  }

  if (file_exists($file)) {
    return response()->download($file, 'TASK MANAGER.postman_collection.json', [
      'Content-Type' => 'application/json',
    ]);
  }

  return response()->json(['error' => 'Collection file not found'], 404);
})->name('postman.download');
