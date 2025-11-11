<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::query()->latest();

        // Filters
        $status = request('status');
        $q = request('q');
        $from = request('from');
        $to = request('to');

        if ($status) {
            $query->where('order_status', $status);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%$q%")
                    ->orWhere('customer_name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            });
        }

        if ($from) {
            $query->whereDate('order_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('order_date', '<=', $to);
        }

        // Export CSV
        if (request('export') === 'csv') {
            $rows = $query->get();
            $csv = fopen('php://temp', 'r+');
            fputcsv($csv, [
                'ID','Order Number','Customer','Email','Phone','Total','Currency','Payment','Status','Date'
            ]);
            foreach ($rows as $o) {
                fputcsv($csv, [
                    $o->id,
                    $o->order_number,
                    $o->customer_name,
                    $o->email,
                    $o->phone,
                    number_format($o->total * $o->currency_rate, 2),
                    $o->currency_symbol,
                    $o->payment_status,
                    $o->order_status,
                    optional($o->order_date)->format('Y-m-d H:i')
                ]);
            }
            rewind($csv);
            $content = stream_get_contents($csv);
            fclose($csv);
            return response($content, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="orders.csv"',
            ]);
        }

        $orders = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Order::count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
            'sales' => Order::sum('total'),
        ];
        return view('admin.orders.index', compact('orders', 'stats', 'status', 'q', 'from', 'to'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:processing,shipped,delivered,cancelled',
        ]);
        $order->update([
            'order_status' => $request->order_status,
        ]);

        return redirect()->back()->with('success', 'Order updated');
    }
}


