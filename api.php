<?php

use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\CheckoutPageController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\WebhookController;
use App\Http\Controllers\Api\V1\WebhookManagementController;
use App\Http\Controllers\Api\V1\ApplicationSettingsController;
use App\Http\Controllers\Api\V1\Admin\ApplicationController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\VerifySignature;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// NOT NEEDED FOR INTEGRATION - Authentication routes (for admin panel only)
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// API v1 routes
Route::prefix('v1')->group(function () {
    // NOT NEEDED FOR INTEGRATION - Admin routes (internal management only)
    Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
        Route::apiResource('applications', ApplicationController::class);
        Route::post('applications/{id}/regenerate-credentials', [ApplicationController::class, 'regenerateCredentials']);
    });
    // NOT NEEDED FOR INTEGRATION - Provider webhook routes (only payment providers call these)
    Route::prefix('webhook')->group(function () {
        Route::post('{provider}', [WebhookController::class, 'handle'])
            ->name('api.webhook.handle');
        Route::post('{provider}/test', [WebhookController::class, 'test'])
            ->name('api.webhook.test');
    });

    // Authenticated API routes (require signature verification) - REQUIRED FOR INTEGRATION
    Route::middleware([VerifySignature::class])->group(function () {
        // Original checkout endpoint (for backward compatibility) - REQUIRED FOR INTEGRATION
        Route::post('checkout', [CheckoutController::class, 'checkout'])
            ->name('api.checkout');

        // New checkout page API endpoints - REQUIRED FOR INTEGRATION
        Route::prefix('checkout')->group(function () {
            Route::get('/', [CheckoutPageController::class, 'show'])
                ->name('api.checkout.show');
            Route::post('/process', [CheckoutPageController::class, 'processProvider'])
                ->name('api.checkout.process');
            Route::get('/success/{transaction}', [CheckoutPageController::class, 'success'])
                ->name('api.checkout.success');
            Route::get('/cancel/{transaction}', [CheckoutPageController::class, 'cancel'])
                ->name('api.checkout.cancel');
        });
         // Transaction endpoints - REQUIRED FOR INTEGRATION
        Route::prefix('transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])
                ->name('api.transactions.index');

            Route::get('stats', [TransactionController::class, 'stats'])
                ->name('api.transactions.stats');

            Route::get('{id}', [TransactionController::class, 'show'])
                ->name('api.transactions.show')
                ->where('id', '[0-9]+');
        });

        // Application Settings endpoints - REQUIRED FOR INTEGRATION
        Route::prefix('settings')->group(function () {
            Route::get('/', [ApplicationSettingsController::class, 'show'])
                ->name('api.settings.show');
            Route::put('webhook', [ApplicationSettingsController::class, 'updateWebhook'])
                ->name('api.settings.webhook.update');
            Route::delete('webhook', [ApplicationSettingsController::class, 'removeWebhook'])
                ->name('api.settings.webhook.remove');
        });

        // User Management endpoints - REQUIRED FOR INTEGRATION
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('api.users.index');
            Route::post('/', [UserController::class, 'store'])
                ->name('api.users.store');
            Route::post('find-or-create', [UserController::class, 'findOrCreate'])
                ->name('api.users.find-or-create');
            Route::get('stats', [UserController::class, 'stats'])
                ->name('api.users.stats');
            Route::get('{identifier}', [UserController::class, 'show'])
                ->name('api.users.show');
            Route::put('{identifier}', [UserController::class, 'update'])
                ->name('api.users.update');
            Route::get('{identifier}/transactions', [UserController::class, 'transactions'])
                ->name('api.users.transactions');
        });

        // NOT NEEDED FOR INTEGRATION - Webhook Management endpoints (internal monitoring only)
        Route::prefix('webhooks')->group(function () {
            Route::get('logs', [WebhookManagementController::class, 'logs'])
                ->name('api.webhooks.logs');
            Route::get('failed', [WebhookManagementController::class, 'failed'])
                ->name('api.webhooks.failed');
            Route::post('retry/{id}', [WebhookManagementController::class, 'retry'])
                ->name('api.webhooks.retry')
                ->where('id', '[0-9]+');
            Route::post('test', [WebhookManagementController::class, 'test'])
                ->name('api.webhooks.test');
        });

        // NOT NEEDED FOR INTEGRATION - Application info endpoint (internal use only)
        Route::get('application', function () {
            $application = request()->attributes->get('application');
            return response()->json([
                'success' => true,
                'data' => [
                    'application' => [
                        'id' => $application->id,
                        'name' => $application->name,
                        'domain' => $application->domain,
                        'webhook_url' => $application->webhook_url,
                        'is_active' => $application->is_active,
                        'created_at' => $application->created_at,
                    ]
                ]
            ]);
        })->name('api.application');
    });
});

// NOT NEEDED FOR INTEGRATION - Health check endpoint (infrastructure monitoring only)
Route::get('health', function () {
    return response()->json([
        'status' => 'healthy',
        'service' => 'Central Payment API',
        'version' => '1.0.0',
        'timestamp' => now()->toISOString(),
    ]);
})->name('api.health');
