<h3>مرحبا {{ $order->customer_name ?? 'عميلنا' }}</h3>
<p>تم تحديث حالة طلبك رقم #{{ $order->id }} إلى: <strong>{{ ucfirst($status) }}</strong>.</p>
<p>شكراً لتسوقك معنا.</p>
