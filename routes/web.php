<?php

use App\Http\Controllers\Home;
use App\Http\Controllers\Login;
use App\Http\Controllers\Signup;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Requests;
use App\Http\Controllers\CountyRequest;
use App\Http\Controllers\Documents;
use App\Http\Controllers\Invoices;
use App\Http\Controllers\Pricing;
use App\Http\Controllers\Aboutus;
use App\Http\Controllers\Clients;
use App\Http\Controllers\Profile;
use App\Http\Controllers\Faq;
use App\Http\Controllers\PDF;
use App\Http\Controllers\Pria;
use App\Http\Controllers\Soap;
use App\Http\Controllers\Users;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;


Route::middleware([RedirectIfAuthenticated::class])->group(function () {
  Route::get('/', [Home::class, 'index'])->name('home');
  Route::get('/login', [Login::class, 'index'])->name('login');
  Route::get('/signup', [Signup::class, 'index'])->name('signup');
  Route::post('/login', [Login::class, 'submit'])->name('login.submit');
  Route::post('/signup', [Signup::class, 'submit'])->name('signup.submit');

  Route::get('/signup-cancel', [Signup::class, 'signupCancel'])->name('signup.cancel');
  Route::get('/signup-success', [Signup::class, 'signupSuccess'])->name('signup.success');
});

Route::group(['middleware' => ['auth']], function () {
  Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');

  Route::get('/requests', [Requests::class, 'index'])->name('requests.index');
  Route::get('/requests/{id}', [Requests::class, 'add'])->name('requests.add');
  Route::post('/requests', [Requests::class, 'submit'])->name('requests.submit');
  Route::delete('/requests/{id}', [Requests::class, 'delete'])->name('requests.delete');
  Route::get('/requests/{id}/notes', [Requests::class, 'notes'])->name('requests.notes');
  Route::post('/read-note', [Requests::class, 'readNote'])->name('notes.read');
  Route::post('/save-note', [Requests::class, 'saveNote'])->name('notes.save');
  Route::get('/requests/{id}/accept', [Requests::class, 'accept'])->name('requests.accept');
  Route::get('/requests/{id}/details', [Requests::class, 'details'])->name('requests.details');

  Route::get('/verify/{id}/request', [CountyRequest::class, 'VerifyRequest'])->name('verify.request');

  Route::get('/clients', [Clients::class, 'index'])->name('clients.index');
  Route::get('/clients/{id}', [Clients::class, 'add'])->name('clients.add');
  Route::post('/clients', [Clients::class, 'submit'])->name('clients.submit');
  Route::delete('/clients/{id}', [Clients::class, 'delete'])->name('clients.delete');
  Route::get('/clients/{id}/details', [Clients::class, 'details'])->name('clients.details');
  Route::get('/clients/{id}/requests', [Clients::class, 'requests'])->name('clients.requests');
  Route::get('/clients/{id}/invoices', [Clients::class, 'invoices'])->name('clients.invoices');
  Route::get('/clients/{id}/password', [Clients::class, 'password'])->name('clients.password');
  Route::post('/clients/{id}/password', [Clients::class, 'passwordSave'])->name('clients.password.save');

  Route::get('/users', [Users::class, 'index'])->name('users.index');
  Route::get('/users/{id}', [Users::class, 'add'])->name('users.add');
  Route::post('/users', [Users::class, 'submit'])->name('users.submit');
  Route::delete('/users/{id}', [Users::class, 'delete'])->name('users.delete');
  Route::get('/users/{id}/password', [Users::class, 'password'])->name('users.password');
  Route::post('/users/{id}/password', [Users::class, 'passwordSave'])->name('users.password.save');

  Route::get('/documents', [Documents::class, 'index'])->name('documents.index');

  Route::get('/invoices', [Invoices::class, 'index'])->name('invoices.index');
  Route::get('/invoices/{id}', [Invoices::class, 'add'])->name('invoices.add');
  Route::post('/invoices', [Invoices::class, 'submit'])->name('invoices.submit');
  Route::delete('/invoices/{id}', [Invoices::class, 'delete'])->name('invoices.delete');
  Route::get('/invoices/{id}/payment', [Invoices::class, 'payment'])->name('invoices.payment');
  Route::get('/invoice-cancel', [Invoices::class, 'invoiceCancel'])->name('invoice.cancel');
  Route::get('/invoice-success', [Invoices::class, 'invoiceSuccess'])->name('invoice.success');

  Route::get('/invoices/{id}/details', [Invoices::class, 'details'])->name('invoices.details');

  Route::get('/pricing', [Pricing::class, 'index'])->name('pricing.index');
  Route::get('/aboutus', [Aboutus::class, 'index'])->name('aboutus.index');
  Route::get('/profile', [Profile::class, 'index'])->name('profile.index');
  Route::post('/update-profile', [Profile::class, 'update'])->name('profile.update');
  Route::post('/change-password', [Profile::class, 'updatePassword'])->name('password.change');
  Route::get('/faq', [Faq::class, 'index'])->name('faq.index');
  Route::get('/helpful-videos', [Faq::class, 'helpfulVideos'])->name('helpful.videos');
  Route::get('/logout', [Dashboard::class, 'logout'])->name('logout');

  Route::post('/uploadfile', [Requests::class, 'uploadFile'])->name('uploadFile');
  Route::delete('/deletefile', [Requests::class, 'deleteFile'])->name('deleteFile');
  Route::get('/counties/{state}', [Requests::class, 'getCounties'])->name('getCounties');
});

Route::group(['middleware' => ['guest']], function () {
  Route::get('/pdf-viewer', [PDF::class, 'viewer'])->name('pdf.viewer');
  Route::get('/pdf-converter', [PDF::class, 'converter'])->name('pdf.converter');
  Route::get('/getfile/{filename}', [PDF::class, 'getFile'])->name('pdf.getFile');

  Route::get('/soap', [Soap::class, 'index'])->name('soap');
  Route::post('/submit-package', [Pria::class, 'submitPackage']);
});
