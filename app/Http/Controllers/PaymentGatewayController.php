<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        return view('backend.payment-gateway.index', [
            'razorpay' => [
                'key_id' => env('RAZORPAY_KEY_ID'),
                'key_secret' => env('RAZORPAY_KEY_SECRET'),
                'mode' => env('RAZORPAY_MODE', 'sandbox'),
                'enabled' => env('RAZORPAY_ENABLED', true),
            ],
        ]);
    }

    public function updateRazorpay(Request $request)
    {
        $validated = $request->validate([
            'key_id' => ['required', 'string', 'max:255'],
            'key_secret' => ['required', 'string', 'max:255'],
            'mode' => ['required', 'in:sandbox,live'],
            'enabled' => ['nullable', 'boolean'],
        ]);

        $this->updateEnv('RAZORPAY_KEY_ID', $validated['key_id']);
        $this->updateEnv('RAZORPAY_KEY_SECRET', $validated['key_secret']);
        $this->updateEnv('RAZORPAY_MODE', $validated['mode']);
        $this->updateEnv(
            'RAZORPAY_ENABLED',
            $request->boolean('enabled') ? 'true' : 'false'
        );

        Artisan::call('config:clear');

        return redirect()
            ->route('payment-gateway.index')
            ->with('success', 'Razorpay settings updated successfully.');
    }

    private function updateEnv(string $key, string $value): void
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            return;
        }

        $env = file_get_contents($path);

        $escapedValue = preg_quote($value, '/');

        if (preg_match("/^{$key}=.*/m", $env)) {
            $env = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $env
            );
        } else {
            $env .= PHP_EOL . "{$key}={$value}";
        }

        file_put_contents($path, $env);
    }
}
