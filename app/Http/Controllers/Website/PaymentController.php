<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|unique:orders,order_number',
            'customer_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'country' => 'nullable|string|max:2',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'shipping' => 'required|numeric',
            'total' => 'required|numeric',
            'currency_symbol' => 'required|string|max:5',
            'currency_rate' => 'required|numeric',
            'payment_method' => 'required|string|in:paypal,stripe',
            'payment_status' => 'required|string|in:paid,pending,failed,refunded',
            'order_status' => 'nullable|string|in:processing,shipped,delivered,cancelled',
            'transaction_id' => 'nullable|string',
            'payer_email' => 'nullable|email',
            'order_date' => 'nullable|date',
        ]);

        $order = Order::create($validated);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
        ]);
    }

    public function stripeCreatePaymentIntent(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.5',
            'currency' => 'required|string|size:3',
        ]);

        $secret = config('services.stripe.secret');
        if (empty($secret)) {
            return response()->json(['error' => 'Stripe not configured'], 400);
        }

        // Guard if library is missing
        if (!class_exists(\Stripe\StripeClient::class)) {
            return response()->json(['error' => 'Stripe SDK not installed'], 500);
        }

        $stripe = new \Stripe\StripeClient($secret);

        $intent = $stripe->paymentIntents->create([
            'amount' => (int) round($data['amount'] * 100),
            'currency' => strtolower($data['currency']),
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return response()->json(['clientSecret' => $intent->client_secret]);
    }

    public function stripeWebhook(Request $request)
    {
        $signature = $request->header('Stripe-Signature');
        $payload = $request->getContent();
        $endpointSecret = config('services.stripe.webhook_secret');

        if (!class_exists(\Stripe\Webhook::class) || empty($endpointSecret)) {
            return response()->json(['ignored' => true]);
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $endpointSecret
            );
        } catch (\Throwable $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            // Optionally, update your order by metadata if sent
        }

        return response()->json(['received' => true]);
    }
}


