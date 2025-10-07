<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdminAuthController;
use App\Http\Controllers\SuperAdminLimitsController;
use App\Http\Controllers\SuperAdminOrganizationLimitsController;

// Public routes
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : view('welcome');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public media sharing
Route::get('/share/{token}', [MediaController::class, 'share'])->name('media.share');

// Public invitation routes
Route::get('/invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');
Route::get('/invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');


// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Media
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::get('/media/create', [MediaController::class, 'create'])->name('media.create');
    Route::get('/media/create/album/{albumName}', [MediaController::class, 'createWithAlbum'])->name('media.create.album');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::get('/media/{filename}/edit-data', [MediaController::class, 'editData'])->name('media.edit-data');
    Route::get('/media/{filename}/edit', [MediaController::class, 'edit'])->name('media.edit');
    Route::get('/media/{filename}/download', [MediaController::class, 'download'])->name('media.download');
    Route::get('/media/{filename}', [MediaController::class, 'show'])->name('media.show');
    Route::put('/media/{filename}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{filename}', [MediaController::class, 'destroy'])->name('media.destroy');
    
    // Albums
    Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
    Route::get('/albums/create', [AlbumController::class, 'create'])->name('albums.create');
    Route::post('/albums', [AlbumController::class, 'store'])->name('albums.store');
    Route::get('/albums/{name}/edit-data', [AlbumController::class, 'editData'])->name('albums.edit-data');
    Route::get('/albums/{name}/edit', [AlbumController::class, 'edit'])->name('albums.edit');
    Route::put('/albums/{name}', [AlbumController::class, 'update'])->name('albums.update');
    Route::delete('/albums/{name}/cover', [AlbumController::class, 'removeCover'])->name('albums.remove-cover');
    Route::delete('/albums/{name}/media/{filename}', [AlbumController::class, 'removePhoto'])->name('albums.remove-photo');
    Route::delete('/albums/{name}', [AlbumController::class, 'destroy'])->name('albums.destroy');
    Route::get('/albums/{name}', [AlbumController::class, 'show'])->name('albums.show');
    
    // Organizations
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::get('/organizations/create', [OrganizationController::class, 'create'])->name('organizations.create');
    Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
    Route::get('/organizations/{name}/unorganized', [OrganizationController::class, 'unorganized'])->name('organizations.unorganized');
    Route::get('/organizations/{name}/edit-data', [OrganizationController::class, 'editData'])->name('organizations.edit-data');
    Route::get('/organizations/{name}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
    Route::get('/organizations/{name}/invite', [OrganizationController::class, 'showInvite'])->name('organizations.invite');
    Route::put('/organizations/{name}', [OrganizationController::class, 'update'])->name('organizations.update');
    Route::delete('/organizations/{name}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');
    Route::delete('/organizations/{name}/cover', [OrganizationController::class, 'removeCover'])->name('organizations.remove-cover');
    Route::get('/organizations/{name}', [OrganizationController::class, 'show'])->name('organizations.show');
    Route::post('/organizations/{name}/invite', [OrganizationController::class, 'invite']);
    Route::delete('/organizations/{name}/users/{userId}', [OrganizationController::class, 'removeUser'])->name('organizations.users.remove');
    Route::post('/organizations/{name}/leave', [OrganizationController::class, 'leave'])->name('organizations.leave');
});

// SuperAdmin authentication routes (public)
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/login', [SuperAdminAuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [SuperAdminAuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('auth.logout');
});

// SuperAdmin protected routes
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', SuperAdminController::class);
    
    // Limits management
    Route::get('/limits', [SuperAdminLimitsController::class, 'index'])->name('limits.index');
    Route::get('/limits/{user}', [SuperAdminLimitsController::class, 'show'])->name('limits.show');
    Route::get('/limits/{user}/edit', [SuperAdminLimitsController::class, 'edit'])->name('limits.edit');
    Route::put('/limits/{user}', [SuperAdminLimitsController::class, 'update'])->name('limits.update');
    Route::post('/limits/{user}/reset', [SuperAdminLimitsController::class, 'reset'])->name('limits.reset');
    Route::get('/limits-settings', [SuperAdminLimitsController::class, 'settings'])->name('limits.settings');
    Route::put('/limits-settings', [SuperAdminLimitsController::class, 'updateSettings'])->name('limits.settings');

    // Organization limits management
    Route::get('/organization-limits', [SuperAdminOrganizationLimitsController::class, 'index'])->name('organization-limits.index');
    Route::get('/organization-limits/{organization}', [SuperAdminOrganizationLimitsController::class, 'show'])->name('organization-limits.show');
    Route::get('/organization-limits/{organization}/edit', [SuperAdminOrganizationLimitsController::class, 'edit'])->name('organization-limits.edit');
    Route::put('/organization-limits/{organization}', [SuperAdminOrganizationLimitsController::class, 'update'])->name('organization-limits.update');
    Route::post('/organization-limits/{organization}/reset', [SuperAdminOrganizationLimitsController::class, 'reset'])->name('organization-limits.reset');
    Route::get('/organization-limits-settings', [SuperAdminOrganizationLimitsController::class, 'settings'])->name('organization-limits.settings');
    Route::put('/organization-limits-settings', [SuperAdminOrganizationLimitsController::class, 'updateSettings'])->name('organization-limits.settings');
});
