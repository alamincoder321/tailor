<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TailorController;
use App\Http\Controllers\UserAccessController;

Route::get("/", [LoginController::class, "showLoginForm"]);
Route::post("/login", [LoginController::class, "login"]);
Route::get("/logout", [LoginController::class, "logout"])->name("logout");

//dashboard route
Route::get("/dashboard", [HomeController::class, "index"]);
//setting route
Route::get("/setting", [SettingController::class, "create"])->name('setting.create');
Route::get("/get-setting", [SettingController::class, "index"])->name('setting.index');
Route::post("/setting", [SettingController::class, "update"])->name('setting.update');
//role route
Route::get("/role", [RoleController::class, "create"])->name('role.create');
Route::get("/get-role", [RoleController::class, "index"])->name('role.index');
Route::post("/role", [RoleController::class, "store"])->name('role.store');
Route::post("/update-role", [RoleController::class, "update"])->name('role.update');
Route::post("/delete-role", [RoleController::class, "destroy"])->name('role.destroy');
//user route
Route::get("/useraccess/{id}", [UserAccessController::class, "permissionEdit"])->name('user.useraccess');
Route::post("/permissionStore", [UserAccessController::class, "permissionStore"])->name('user.permissionStore');
Route::get("/user", [UserAccessController::class, "create"])->name('user.create');
Route::get("/user", [UserAccessController::class, "create"])->name('user.create');
Route::get("/get-user", [UserAccessController::class, "index"])->name('user.index');
Route::post("/user", [UserAccessController::class, "store"])->name('user.store');
Route::post("/update-user", [UserAccessController::class, "update"])->name('user.update');
Route::post("/delete-user", [UserAccessController::class, "destroy"])->name('user.destroy');
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
//tailor route
Route::get("/tailor", [TailorController::class, "create"])->name('tailor.create');
Route::get("/get-tailor", [TailorController::class, "index"])->name('tailor.index');
Route::post("/tailor", [TailorController::class, "store"])->name('tailor.store');
Route::post("/update-tailor", [TailorController::class, "update"])->name('tailor.update');
Route::post("/delete-tailor", [TailorController::class, "destroy"])->name('tailor.destroy');
//customer route
Route::get("/customer", [CustomerController::class, "create"])->name('customer.create');
Route::get("/get-customer", [CustomerController::class, "index"])->name('customer.index');
Route::post("/customer", [CustomerController::class, "store"])->name('customer.store');
Route::post("/update-customer", [CustomerController::class, "update"])->name('customer.update');
Route::post("/delete-customer", [CustomerController::class, "destroy"])->name('customer.destroy');
//product route
Route::get("/product", [ProductController::class, "create"])->name('product.create');
Route::get("/get-product", [ProductController::class, "index"])->name('product.index');
Route::post("/product", [ProductController::class, "store"])->name('product.store');
Route::post("/update-product", [ProductController::class, "update"])->name('product.update');
Route::post("/delete-product", [ProductController::class, "destroy"])->name('product.destroy');

//order route
Route::get("/order/{id?}", [OrderController::class, "create"])->name('order.create');
Route::get("/manage-order", [OrderController::class, "manage"])->name('order.manage');
Route::post("/get-order", [OrderController::class, "index"])->name('order.index');
Route::post("/order", [OrderController::class, "store"])->name('order.store');
Route::post("/update-order", [OrderController::class, "update"])->name('order.update');
Route::post("/delete-order", [OrderController::class, "destroy"])->name('order.destroy');