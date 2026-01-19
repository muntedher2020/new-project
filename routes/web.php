<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Crm;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\UserAccounts\UserAccountsController;
use App\Http\Controllers\PermissionsRoles\Roles\RolesController;
use App\Http\Controllers\Backend\Employments\EmploymentsController;
use App\Http\Controllers\Backend\ElectronicForms\ElectronicFormsController;
use App\Http\Controllers\PermissionsRoles\Permissions\PermissionsController;
use App\Http\Controllers\Backend\ElectronicForms\FormFieldsManagerController;
use App\Http\Controllers\Backend\ElectronicForms\ElectronicFormPrintController;
use App\Http\Controllers\Backend\ElectronicForms\ElectronicFormTcpdfExportController;

// Main Page Route
// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);

Route::get('/', [FrontendController::class, 'home'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // Middleware Owners|Admin
    Route::middleware(['role:OWNER|Admin'])->group(function () {
        Route::GROUP(['prefix' => 'Backend'], function () {
            Route::get('Dashboard', [DashboardController::class, 'index'])->name('dashboard');

            Route::GET('Electronic-Forms', [ElectronicFormsController::class, 'index'])->name('ElectronicForms');
            Route::GET('Electronic-Forms/export-pdf-tcpdf', [ElectronicFormTcpdfExportController::class, 'exportPdf'])->name('ElectronicForms.export.pdf.tcpdf');
            Route::GET('Electronic-Forms/print-view', [ElectronicFormPrintController::class, 'printView'])->name('ElectronicForms.print.view');   
            // إدارة حقول الاستمارة
            Route::get('Electronic-Forms/{form}/fields', [FormFieldsManagerController::class, 'index'])
                ->name('forms.fields.manage')
                ->middleware('can:form-field-manage');
        });
        
        // Users Accounts
        Route::RESOURCE('User-Accounts', UserAccountsController::class);

        // Roles & Permission
        Route::GROUP(['prefix' => 'Permissions&Roles'], function () {
            Route::RESOURCE('Permissions', PermissionsController::class)->middleware('role:OWNER');
            Route::RESOURCE('Roles', RolesController::class)->middleware('role:OWNER|Admin');
        });
    });

    Route::get('/dashboard/crm', [Crm::class, 'index'])->name('dashboard-crm');
});

Route::get('Employments', [EmploymentsController::class, 'index'])
     ->name('employments');

// Routes للجمهور (بدون تسجيل دخول)
Route::get('/registration-form/{slug}', [EmploymentsController::class, 'showForm'])
     ->name('forms.public.show');
Route::post('/registration-form/{slug}/submit', [EmploymentsController::class, 'submitResponse'])
     ->name('forms.public.submit');
Route::get('/registration-form/{slug}/thankyou', [ElectronicFormsController::class, 'thankyou'])
     ->name('forms.public.thankyou');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
