<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Order confirmation sent to the customer. */
class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your ' . setting('site_name', 'Shop Sarkar') . ' order ' . $this->order->order_number . ' is confirmed',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-customer');
    }
}
