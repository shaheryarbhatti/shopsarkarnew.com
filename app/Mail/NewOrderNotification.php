<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** New-order alert sent to the store admin. */
class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🛒 New order ' . $this->order->order_number . ' — ' . money($this->order->total),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-admin');
    }
}
