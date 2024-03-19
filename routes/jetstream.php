<?php

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Laravel\Jetstream\Http\Controllers\Livewire\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Livewire\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Livewire\TeamController;
use Laravel\Jetstream\Http\Controllers\Livewire\TermsOfServiceController;
use Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController;
use Laravel\Jetstream\Http\Controllers\TeamInvitationController;
use Laravel\Jetstream\Jetstream;

Route::group(['middleware' => config('fortify.middleware', ['admins'])], function () {
    Route::prefix('admin')->group(function() {
        if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
            Route::get('/terms-of-service', [TermsOfServiceController::class, 'show'])->name('terms.show');
            Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
        }

        $authMiddleware = config('fortify.guard')
            ? 'auth:'.config('fortify.guard')
            : 'auth';

        $authSessionMiddleware = config('fortify.auth_session', false)
            ? config('fortify.auth_session')
            : null;

        Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
            // User & Profile...
            Route::get('/me/profile', [UserProfileController::class, 'show'])->name('profile.show');

            Route::group(['middleware' => 'verified'], function () {
                // API...
                if (Jetstream::hasApiFeatures()) {
                    Route::get('/me/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
                }

                // Teams...
                if (Jetstream::hasTeamFeatures()) {
                    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
                    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
                    Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');

                    Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
                        ->middleware(['signed'])
                        ->name('team-invitations.accept');
                }
            });
        });
    });
});
