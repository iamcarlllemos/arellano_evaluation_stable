<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\RoutePath;

Route::group(['middleware' => config('fortify.middleware', ['admins'])], function () {
    Route::prefix('admin')->group(function() {
        $enableViews = config('fortify.views', true);

        if ($enableViews) {
            Route::get(RoutePath::for('login', '/admin/login'), [AuthenticatedSessionController::class, 'create'])
                ->middleware(['guest:'.config('fortify.guard')])
                ->name('admin.login');
        }

        $limiter = config('fortify.limiters.login');
        $twoFactorLimiter = config('fortify.limiters.two-factor');
        $verificationLimiter = config('fortify.limiters.verification', '6,1');

        Route::post(RoutePath::for('login', '/admin/login'), [AuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter([
                'guest:'.config('fortify.guard'),
                $limiter ? 'throttle:'.$limiter : null,
            ]));

        Route::any(RoutePath::for('signout', '/admin/logout'), [AuthenticatedSessionController::class, 'destroy'])
            ->name('admin.logout');

        if (Features::enabled(Features::resetPasswords())) {
            if ($enableViews) {
                Route::get(RoutePath::for('password.request', '/forgot-password'), [PasswordResetLinkController::class, 'create'])
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('password.request');

                Route::get(RoutePath::for('password.reset', '/reset-password/{token}'), [NewPasswordController::class, 'create'])
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('password.reset');
            }

            Route::post(RoutePath::for('password.email', '/forgot-password'), [PasswordResetLinkController::class, 'store'])
                ->middleware(['guest:'.config('fortify.guard')])
                ->name('password.email');

            Route::post(RoutePath::for('password.update', '/reset-password'), [NewPasswordController::class, 'store'])
                ->middleware(['guest:'.config('fortify.guard')])
                ->name('password.update');
        }

        if (Features::enabled(Features::registration())) {
            if ($enableViews) {
                Route::get(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'create'])
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('admin.register');
            }

            Route::post(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'store'])
                ->middleware(['guest:'.config('fortify.guard')]);
        }

        if (Features::enabled(Features::emailVerification())) {
            if ($enableViews) {
                Route::get(RoutePath::for('verification.notice', '/email/verify'), [EmailVerificationPromptController::class, '__invoke'])
                    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                    ->name('verification.notice');
            }

            Route::get(RoutePath::for('verification.verify', '/email/verify/{id}/{hash}'), [VerifyEmailController::class, '__invoke'])
                ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'signed', 'throttle:'.$verificationLimiter])
                ->name('verification.verify');

            Route::post(RoutePath::for('verification.send', '/email/verification-notification'), [EmailVerificationNotificationController::class, 'store'])
                ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'throttle:'.$verificationLimiter])
                ->name('verification.send');
        }

        if (Features::enabled(Features::updateProfileInformation())) {
            Route::put(RoutePath::for('user-profile-information.update', '/account/profile-information'), [ProfileInformationController::class, 'update'])
                ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                ->name('user-profile-information.update');
        }

        if (Features::enabled(Features::updatePasswords())) {
            Route::put(RoutePath::for('user-password.update', '/account/password'), [PasswordController::class, 'update'])
                ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                ->name('user-password.update');
        }

        if ($enableViews) {
            Route::get(RoutePath::for('password.confirm', '/account/confirm-password'), [ConfirmablePasswordController::class, 'show'])
                ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')]);
        }

        Route::get(RoutePath::for('password.confirmation', '/account/confirmed-password-status'), [ConfirmedPasswordStatusController::class, 'show'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
            ->name('password.confirmation');

        Route::post(RoutePath::for('password.confirm', '/account/confirm-password'), [ConfirmablePasswordController::class, 'store'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
            ->name('password.confirm');

        if (Features::enabled(Features::twoFactorAuthentication())) {
            if ($enableViews) {
                Route::get(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'create'])
                    ->middleware(['guest:'.config('fortify.guard')])
                    ->name('two-factor.login');
            }

            Route::post(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'store'])
                ->middleware(array_filter([
                    'guest:'.config('fortify.guard'),
                    $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
                ]));

            $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
                ? [config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'password.confirm']
                : [config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')];

            Route::post(RoutePath::for('two-factor.enable', '/account/two-factor-authentication'), [TwoFactorAuthenticationController::class, 'store'])
                ->middleware($twoFactorMiddleware)
                ->name('two-factor.enable');

            Route::post(RoutePath::for('two-factor.confirm', '/account/confirmed-two-factor-authentication'), [ConfirmedTwoFactorAuthenticationController::class, 'store'])
                ->middleware($twoFactorMiddleware)
                ->name('two-factor.confirm');

            Route::delete(RoutePath::for('two-factor.disable', '/account/two-factor-authentication'), [TwoFactorAuthenticationController::class, 'destroy'])
                ->middleware($twoFactorMiddleware)
                ->name('two-factor.disable');

            Route::get(RoutePath::for('two-factor.qr-code', '/account/two-factor-qr-code'), [TwoFactorQrCodeController::class, 'show'])
                ->middleware($twoFactorMiddleware)
                ->name('two-factor.qr-code');

            Route::get(RoutePath::for('two-factor.secret-key', '/account/two-factor-secret-key'), [TwoFactorSecretKeyController::class, 'show'])
                ->middleware($twoFactorMiddleware)
                ->name('two-factor.secret-key');

            Route::get(RoutePath::for('two-factor.recovery-codes', '/account/two-factor-recovery-codes'), [RecoveryCodeController::class, 'index'])
                ->middleware($twoFactorMiddleware)
                ->name('two-factor.recovery-codes');

            Route::post(RoutePath::for('two-factor.recovery-codes', '/account/two-factor-recovery-codes'), [RecoveryCodeController::class, 'store'])
                ->middleware($twoFactorMiddleware);
        }
    });
});
