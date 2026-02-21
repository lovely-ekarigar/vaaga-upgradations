<?php

namespace App\Http\Controllers\Traits;

/**
 * FIXED: Invoice Generator Trait
 * The ConsoleTVs\Invoices package may not be installed.
 * This trait now provides fallback functionality.
 */

class InvoiceGenerator
{
    public $taxData = null;
    public $discount = null;
    public $total = null;
    public $orderref = null;
    public $month = null;
    public $citems = [];
    public $items = [];
    public $name = 'Invoice';
    public $invoiceNumber = null;
    public $customerData = [];

    public function __construct($name = 'Invoice')
    {
        $this->name = $name;
    }

    /**
     * Set invoice number
     */
    public function number($number)
    {
        $this->invoiceNumber = $number;
        return $this;
    }

    /**
     * Add an item to the invoice
     */
    public function addItem($name, $price, $qty = 1, $id = '')
    {
        $this->items[] = [
            'name' => $name,
            'price' => $price,
            'qty' => $qty,
            'id' => $id,
            'total' => $price * $qty
        ];
        return $this;
    }

    /**
     * Set customer data
     */
    public function customer(array $data)
    {
        $this->customerData = $data;
        return $this;
    }

    public function addTaxData($taxData)
    {
        $this->taxData = $taxData;
        return $this;
    }

    public function addCitems($title, $price, $qty, $id)
    {
        $this->citems[] = array("name" => $title, "price" => $price, "qty" => $qty, "id" => $id);
        return $this;
    }

    public function addMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    public function addDiscountData($coupon)
    {
        $this->discount = number_format($coupon, 2);
        return $this;
    }

    public function addOrderInfo($orderref)
    {
        $this->orderref = $orderref;
        return $this;
    }

    public function addTotal($total)
    {
        $this->total = number_format($total, 2);
        return $this;
    }

    /**
     * Show the invoice (fallback - returns HTML response)
     */
    public function show($filename = null)
    {
        $html = $this->generateInvoiceHtml();
        return response($html, 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    /**
     * Download the invoice (fallback - returns HTML as download)
     */
    public function download($filename = null)
    {
        $html = $this->generateInvoiceHtml();
        $filename = $filename ?? 'invoice_' . ($this->orderref ?? time()) . '.html';
        
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate professional invoice HTML
     */
    private function generateInvoiceHtml()
    {
        $itemsHtml = '';
        $subtotal = 0;
        
        foreach ($this->items as $item) {
            $itemTotal = $item['price'] * $item['qty'];
            $subtotal += $itemTotal;
            $itemsHtml .= "
                <tr>
                    <td>{$item['name']}</td>
                    <td>{$item['id']}</td>
                    <td style='text-align:center'>{$item['qty']}</td>
                    <td style='text-align:right'>₹" . number_format($item['price'], 2) . "</td>
                    <td style='text-align:right'>₹" . number_format($itemTotal, 2) . "</td>
                </tr>
            ";
        }

        $customerName = $this->customerData['name'] ?? 'Customer';
        $customerEmail = $this->customerData['email'] ?? '';
        $customerPhone = $this->customerData['phone'] ?? '';
        $customerId = $this->customerData['id'] ?? '';

        return "<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {$this->orderref}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .invoice-container { max-width: 800px; margin: 0 auto; background: white; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .invoice-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; }
        .invoice-header h1 { font-size: 28px; margin-bottom: 5px; }
        .invoice-header p { opacity: 0.9; }
        .invoice-body { padding: 30px; }
        .invoice-info { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info-box { flex: 1; }
        .info-box h4 { color: #667eea; margin-bottom: 10px; font-size: 14px; text-transform: uppercase; }
        .info-box p { color: #666; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #f8f9fa; color: #333; padding: 12px; text-align: left; font-weight: 600; border-bottom: 2px solid #667eea; }
        td { padding: 12px; border-bottom: 1px solid #eee; color: #555; }
        tr:hover { background: #f8f9fa; }
        .totals { margin-top: 20px; border-top: 2px solid #eee; padding-top: 20px; }
        .total-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .total-row.grand { font-size: 18px; font-weight: bold; color: #667eea; border-top: 2px solid #667eea; margin-top: 10px; padding-top: 10px; }
        .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; color: #888; font-size: 12px; }
        .badge { background: #28a745; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; }
        @media print { body { background: white; } .invoice-container { box-shadow: none; } }
    </style>
</head>
<body>
    <div class='invoice-container'>
        <div class='invoice-header'>
            <h1>INVOICE</h1>
            <p>Order: #{$this->orderref} | Invoice #: {$this->invoiceNumber}</p>
            " . ($this->month ? "<p style='margin-top:10px'>Period: {$this->month}</p>" : "") . "
        </div>
        <div class='invoice-body'>
            <div class='invoice-info'>
                <div class='info-box'>
                    <h4>Bill To</h4>
                    <p><strong>{$customerName}</strong><br>
                    Customer ID: {$customerId}<br>
                    Email: {$customerEmail}<br>
                    Phone: {$customerPhone}</p>
                </div>
                <div class='info-box' style='text-align:right'>
                    <h4>Invoice Details</h4>
                    <p><strong>Date:</strong> " . date('d M Y') . "<br>
                    <strong>Status:</strong> <span class='badge'>PAID</span></p>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Item ID</th>
                        <th style='text-align:center'>Qty</th>
                        <th style='text-align:right'>Price</th>
                        <th style='text-align:right'>Total</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
            </table>
            <div class='totals'>
                " . ($this->discount ? "<div class='total-row'><span>Discount:</span><span>₹{$this->discount}</span></div>" : "") . "
                " . ($this->taxData ? "<div class='total-row'><span>GST/Tax:</span><span>₹{$this->taxData}</span></div>" : "") . "
                <div class='total-row grand'><span>Grand Total:</span><span>₹{$this->total}</span></div>
            </div>
        </div>
        <div class='footer'>
            <p>Thank you for your business! | VaaGa Academy</p>
        </div>
    </div>
</body>
</html>";
    }
}
