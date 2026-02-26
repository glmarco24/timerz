<?php
use Illuminate\Support\Facades\Route;

// Serve the SPA shell for all non-API routes so Vue Router history works
Route::view('/', 'welcome');
Route::view('/{any}', 'welcome')->where('any', '^(?!api).*$');
