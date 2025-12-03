<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use App\Services\EmailService;
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
            'payment_method' => 'required|string|in:paypal,stripe,cod',
            'payment_status' => 'required|string|in:paid,pending,failed,refunded',
            'order_status' => 'nullable|string|in:processing,shipped,delivered,cancelled',
            'transaction_id' => 'nullable|string',
            'payer_email' => 'nullable|email',
            'order_date' => 'nullable|date',
        ], [
            'order_number.required' => 'رقم الطلب مطلوب',
            'order_number.unique' => 'رقم الطلب هذا موجود بالفعل',
            'customer_name.required' => 'اسم العميل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صحيحاً',
            'country.max' => 'يجب ألا يتجاوز رمز الدولة حرفين',
            'items.required' => 'يجب إضافة عنصر واحد على الأقل',
            'items.array' => 'يجب أن تكون العناصر مصفوفة',
            'subtotal.required' => 'المجموع الفرعي مطلوب',
            'subtotal.numeric' => 'يجب أن يكون المجموع الفرعي رقماً',
            'shipping.required' => 'تكلفة الشحن مطلوبة',
            'shipping.numeric' => 'يجب أن تكون تكلفة الشحن رقماً',
            'total.required' => 'المجموع الإجمالي مطلوب',
            'total.numeric' => 'يجب أن يكون المجموع الإجمالي رقماً',
            'currency_symbol.required' => 'رمز العملة مطلوب',
            'currency_symbol.max' => 'يجب ألا يتجاوز رمز العملة 5 أحرف',
            'currency_rate.required' => 'سعر العملة مطلوب',
            'currency_rate.numeric' => 'يجب أن يكون سعر العملة رقماً',
            'payment_method.required' => 'طريقة الدفع مطلوبة',
            'payment_method.in' => 'طريقة الدفع يجب أن تكون: PayPal، Stripe، أو الدفع عند الاستلام',
            'payment_status.required' => 'حالة الدفع مطلوبة',
            'payment_status.in' => 'حالة الدفع يجب أن تكون: مدفوع، قيد الانتظار، فشل، أو مسترد',
            'order_status.in' => 'حالة الطلب يجب أن تكون: قيد المعالجة، تم الشحن، تم التوصيل، أو ملغي',
            'payer_email.email' => 'يجب أن يكون بريد الدافع صحيحاً',
            'order_date.date' => 'يجب أن يكون تاريخ الطلب تاريخاً صحيحاً',
        ]);

        $order = Order::create($validated);

        // Send order notification email using EmailService
        try {
            $this->sendOrderNotificationEmail($order);
        } catch (\Exception $e) {
            Log::error('Failed to send order notification: ' . $e->getMessage());
        }

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
        ], [
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'يجب أن يكون المبلغ رقماً',
            'amount.min' => 'يجب أن يكون المبلغ 0.5 على الأقل',
            'currency.required' => 'العملة مطلوبة',
            'currency.size' => 'رمز العملة يجب أن يكون 3 أحرف',
        ]);

        // Get Stripe config from PaymentService
        $config = PaymentService::getStripeConfig();
        $secret = $config['secret'] ?? config('services.stripe.secret');

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

    /**
     * Send order notification email
     */
    private function sendOrderNotificationEmail(Order $order)
    {
        $emailConfig = EmailService::getConfig();
        $notificationEmail = $emailConfig['order_notification_email'];

        if (empty($notificationEmail)) {
            return;
        }

        // Send email notification (you can use Laravel's Mail or a mailable class)
        // \Mail::to($notificationEmail)->send(new \App\Mail\OrderStatusChanged($order));
    }
}



