<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SettingController;

Route::get("/", [LoginController::class, "showLoginForm"]);
Route::post("/login", [LoginController::class, "login"]);
Route::get("/logout", [LoginController::class, "logout"])->name("logout");

//dashboard route
Route::get("/dashboard", [HomeController::class, "index"]);
//setting route
Route::get("/setting", [SettingController::class, "create"])->name('setting.create');
Route::get("/get-setting", [SettingController::class, "index"])->name('setting.index');
Route::post("/setting", [SettingController::class, "update"])->name('setting.update');
//brand route
Route::get("/brand", [BrandController::class, "create"])->name('brand.create');
Route::get("/get-brand", [BrandController::class, "index"])->name('brand.index');
Route::post("/brand", [BrandController::class, "store"])->name('brand.store');
Route::post("/update-brand", [BrandController::class, "update"])->name('brand.update');
Route::post("/delete-brand", [BrandController::class, "destroy"])->name('brand.destroy');
//category route
Route::get("/category", [CategoryController::class, "create"])->name('category.create');
Route::get("/get-category", [CategoryController::class, "index"])->name('category.index');
Route::post("/category", [CategoryController::class, "store"])->name('category.store');
Route::post("/update-category", [CategoryController::class, "update"])->name('category.update');
Route::post("/delete-category", [CategoryController::class, "destroy"])->name('category.destroy');
//designation route
Route::get("/designation", [DesignationController::class, "create"])->name('designation.create');
Route::get("/get-designation", [DesignationController::class, "index"])->name('designation.index');
Route::post("/designation", [DesignationController::class, "store"])->name('designation.store');
Route::post("/update-designation", [DesignationController::class, "update"])->name('designation.update');
Route::post("/delete-designation", [DesignationController::class, "destroy"])->name('designation.destroy');
//employee route
Route::get("/employee/{id?}", [EmployeeController::class, "create"])->name('employee.create');
Route::get("/manage-employee", [EmployeeController::class, "manage"])->name('employee.manage');
Route::post("/get-employee", [EmployeeController::class, "index"])->name('employee.index');
Route::post("/employee", [EmployeeController::class, "store"])->name('employee.store');
Route::post("/update-employee", [EmployeeController::class, "update"])->name('employee.update');
Route::post("/delete-employee", [EmployeeController::class, "destroy"])->name('employee.destroy');
