<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;

    public function __construct($order, $status)
    {
        $this->order = $order;
        $this->status = $status;
        
    }

    public function build()
    {
        return $this->subject('تحديث حالة طلبك - رقم: ' . $this->order->id)
                    ->markdown('emails.order.status_changed')
                    ->with([
                        'order' => $this->order,
                        'status' => $this->status,
                    ]);
    }
}
