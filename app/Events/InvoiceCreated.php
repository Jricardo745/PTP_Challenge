<?php

namespace App\Events;

use App\Invoice;
use Illuminate\Queue\SerializesModels;

class InvoiceCreated
{
    use SerializesModels;

    /**
     * @var Invoice
     */
    public $invoice;

    /**
     * Create a new event instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
}