<?php

namespace App\Http\Controllers\Traits;

use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceGenerator
{
    public $taxData = null;
    public $discount = null;
    public $total = null;
    public $orderref = null;
    public $month = null;
    public $invoiceDate = null;
    public $citems = [];
    public $items = [];
    public $name = 'Invoice';
    public $invoiceNumber = null;
    public $customerData = [];
    public $remark = null;

    public function __construct($name = 'Invoice')
    {
        $this->name = $name;
    }

    public function number($number)
    {
        $this->invoiceNumber = $number;
        return $this;
    }

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

    public function addDate($date)
    {
        $this->invoiceDate = $date;
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

    public function addRemark($remark)
    {
        $this->remark = $remark;
        return $this;
    }

    public function addTotal($total)
    {
        $this->total = number_format($total, 2);
        return $this;
    }

    public function show($filename = null)
    {
        $html = $this->generateInvoiceHtml();
        return response($html, 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    public function download($filename = null)
    {
        $html = $this->generateInvoiceHtml(true);
        $filename = $filename ?? 'invoice_' . ($this->invoiceNumber ?? time()) . '.pdf';

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download($filename);
    }

    private function generateInvoiceHtml($forPdf = false)
    {
        $itemsHtml = '';
        $subtotal = 0;
        $sn = 0;

       foreach ($this->items as $item) {
    $sn++;
    $itemTotal = $item['price'] * $item['qty'];
    $subtotal += $itemTotal;

    $escapedName = e($item['name']);

    $remarkHtml = '';
    if (!empty($this->remark)) {
        $remarkText = e($this->remark);
        $remarkHtml = "<div style='font-size:11px; color:#777; margin-top:4px;'>{$remarkText}</div>";
    }

    $itemsHtml .= "<tr>
        <td style='text-align:center;'>{$sn}</td>
        <td>{$escapedName} {$remarkHtml}</td>
        <td style='text-align:center;'>{$item['qty']}</td>
        <td style='text-align:right;'>Rs. " . number_format($item['price'], 2) . "</td>
        <td style='text-align:right;'>Rs. " . number_format($itemTotal, 2) . "</td>
    </tr>";
}


        $customerName = e($this->customerData['name'] ?? 'Customer');
        $customerEmail = e($this->customerData['email'] ?? '');
        $customerPhone = e($this->customerData['phone'] ?? '');
        $customerId = $this->customerData['id'] ?? '';

        $invoiceDate = $this->invoiceDate
            ? date('d M, Y', strtotime($this->invoiceDate))
            : date('d M, Y');

        $invoiceNo = 'INV-' . str_pad($this->invoiceNumber, 5, '0', STR_PAD_LEFT);
        $referenceNo = $this->orderref ?: 'N/A';
        $billingMonth = $this->month ?: 'One-Time';
        $subtotalFormatted = number_format($subtotal, 2);
        $appName = config('app.name', 'VaaGa Academy');
        $logoSrc = $this->getInvoiceLogoDataUri($forPdf);
        $brandHeader = $logoSrc
            ? '<div class="brand-logo"><img src="' . $logoSrc . '" alt="' . e($appName . ' logo') . '"></div>'
            : '<h1>' . e($appName) . '</h1>';

        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - ' . $invoiceNo . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #fff; color: #333; font-size: 13px; }
        @page { margin: 30px; }
        .invoice-wrap {
    max-width: 800px;
    margin: 0 auto;
    padding: 30px;
    padding-bottom: 100px;
    border: 1px solid #eee;
    border-top: 5px solid #FEBC5A;
    position: relative;
    min-height: calc(100vh - 60px);
}
        .header-table { width: 100%; margin-bottom: 25px; }
        .header-table td { vertical-align: top; }
        .brand-logo { margin-bottom: 12px; }
        .brand-logo img { display: block; max-width: 260px; height: auto; }
        .brand h1 { font-size: 26px; color: #2c3e50; margin-bottom: 2px; }
        .brand p {
    color: #7f8c8d;
    font-size: 14px;
    line-height: 22px;
}
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 32px; color: #e74c3c; letter-spacing: 2px; margin-bottom: 5px; }
        .invoice-title p {
    color: #555;
    font-size: 12px;
    line-height: 22px;
    margin-top: 5px;
}

.invoice-title p strong {
    display: inline-block;
    min-width: 110px;
}
        .invoice-title strong { color: #333; }
        .divider { border: none; border-top: 2px solid #e74c3c; margin: 15px 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; padding: 8px 0; }
        .info-label { font-size: 11px; color: #e74c3c; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; margin-bottom: 6px; }
        .info-content { color: #555; line-height: 1.7; font-size: 12px; }
        .info-content strong { color: #333; }
       .items-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.items-table thead th {
    background: #2c3e50;
    color: #fff;
    padding: 12px 12px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.items-table th,
.items-table td {
    padding: 12px 12px;
    border-bottom: 1px solid #ecf0f1;
    vertical-align: middle;
}

.items-table tbody tr {
    height: 40px;
    page-break-inside: avoid;
}

.items-table tbody td {
    color: #555;
    font-size: 12px;
    line-height: 18px;
}

.items-table tbody tr:nth-child(even) {
    background: #f9f9f9;
}
        .summary-wrap { width: 100%; margin-top: 10px; }
        .summary-wrap td { vertical-align: top; }
        .summary-table { width: 280px; float: right; }
        .summary-table td { padding: 6px 10px; font-size: 12px; }
        .summary-table .label { color: #777; text-align: left; }
        .summary-table .value { color: #333; text-align: right; font-weight: 500; }
        .summary-table .grand-total td { border-top: 2px solid #2c3e50; font-size: 15px; font-weight: bold; padding-top: 10px; }
        .summary-table .grand-total .label { color: #2c3e50; }
        .summary-table .grand-total .value { color: #e74c3c; }
        .status-badge { display: inline-block; background: #27ae60; color: #fff; padding: 7px 13px; border-radius: 3px; font-size: 11px; font-weight: bold; letter-spacing: 1px;}
        .footer {
    position: absolute;
    bottom: 30px;
    left: 0;
    width: 100%;
    padding: 15px 30px 0 30px;
    border-top: 1px solid #ecf0f1;
    text-align: center;
    color: #999;
    font-size: 11px;
    line-height: 1.8;
}
        .footer strong { color: #666; }
        .note-box { background: #fef9e7; border-left: 3px solid #f39c12;  
        line-height: 2.0;
        word-wrap: break-word; 
         white-space: normal;
        padding: 10px 14px; margin-top: 20px; font-size: 11px; color: #7d6608; }
        table {
    page-break-inside: auto;
}

tr {
    page-break-inside: avoid;
    page-break-after: auto;
}

td {
    word-break: break-word;
}
        @media print {
            body { background: #fff; }
            .invoice-wrap { padding: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="invoice-wrap">

    <table class="header-table">
        <tr>
            <td class="brand" style="width:50%;">
                ' . $brandHeader . '
              <table style="font-size:14px; color:#7f8c8d; margin-top:6px;">
<tr>
<td style="padding:4px 0; font-weight:bold; color:#2c3e50;">VaaGa Academy</td>
</tr>

<tr>
<td style="padding:4px 0;">Sector-86, Gurugram</td>
</tr>

<tr>
<td style="padding:4px 0;">Haryana 122004</td>
</tr>

<tr>
<td style="padding:4px 0;">Website: www.vaagaacademy.com</td>
</tr>

<tr>
<td style="padding:4px 0;">Email: info@vaagaacademy.com</td>
</tr>
</table>
            </td>
            <td class="invoice-title" style="width:50%;">
                <h2>INVOICE</h2>
                <p>
                   <div style="margin-bottom:6px;">
<strong>Invoice No:</strong> ' . $invoiceNo . '
</div>

<div style="margin-bottom:6px;">
<strong>Date:</strong> ' . $invoiceDate . '
</div>

<div style="margin-bottom:6px;">
<strong>Reference No:</strong> ' . e($referenceNo) . '
</div>

<div style="margin-bottom:6px;">
<strong>Status:</strong> <span class="status-badge">PAID</span>
</div>
                </p>
            </td>
        </tr>
    </table>

    <hr class="divider">

    <table class="info-table">
        <tr>
            <td style="width:50%;">
<div class="info-label">Bill To</div>

<table style="font-size:12px; margin-top:5px;">
<tr>
<td style="padding:4px 0;"><strong>' . $customerName . '</strong></td>
</tr>

<tr>
<td style="padding:4px 0;">Email: ' . $customerEmail . '</td>
</tr>

<tr>
<td style="padding:4px 0;">Phone: ' . $customerPhone . '</td>
</tr>
</table>

</td>
            <td style="width:50%; text-align:right;">
                <div class="info-label">Billing Period</div>
                <div class="info-content">
                    <strong>' . e($billingMonth) . '</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width:8%; text-align:center;">S.No</th>
                <th style="width:42%;">Description</th>
                <th style="width:10%; text-align:center;">Qty</th>
                <th style="width:20%; text-align:right;">Unit Price</th>
                <th style="width:20%; text-align:right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            ' . $itemsHtml . '
        </tbody>
    </table>

    <table class="summary-wrap">
        <tr>
            <td style="width:55%;">
                <div class="note-box">
                    <strong>Note:</strong> This is a computer-generated invoice and does not require a physical signature 
                </div>
            </td>
            <td style="width:45%;">
                <table class="summary-table">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="value">Rs. ' . $subtotalFormatted . '</td>
                    </tr>
                    ' . ((float) str_replace(',', '', (string)$this->discount) > 0 ? '<tr>
                        <td class="label">Discount</td>
                        <td class="value" style="color:#27ae60;">- Rs. ' . $this->discount . '</td>
                    </tr>' : '') . '
   
               
                    <tr class="grand-total">
                        <td class="label">Grand Total</td>
                        <td class="value">Rs. ' . $this->total . '</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
    <table width="100%">
        <tr>
            <td style="text-align:center;">
                <strong>Thank you for choosing ' . e($appName) . '!</strong>
               
            </td>
        </tr>
        <tr>
            <td style="text-align:center;">
               
                If you have any questions about this invoice, please Contact Support team.
            </td>
        </tr>
    </table>
</div>

</div>
</body>
</html>';
    }

    private function getInvoiceLogoDataUri($forPdf = false)
    {
        $logoCandidates = [];

        if ($forPdf && ! extension_loaded('gd')) {
            $logoCandidates = [
                ['path' => public_path('newassets/img/logo-invoice.jpg'), 'mime' => 'image/jpeg'],
            ];
        } else {
            $logoCandidates = [
                ['path' => public_path('newassets/img/logo.png'), 'mime' => 'image/png'],
                ['path' => public_path('ltlogo.png'), 'mime' => 'image/png'],
                ['path' => public_path('old_public/newassets/img/logo.png'), 'mime' => 'image/png'],
                ['path' => public_path('newassets/img/logo-invoice.jpg'), 'mime' => 'image/jpeg'],
            ];
        }

        foreach ($logoCandidates as $logoCandidate) {
            $logoPath = $logoCandidate['path'];

            if (! is_file($logoPath) || ! is_readable($logoPath)) {
                continue;
            }

            $logoContents = file_get_contents($logoPath);

            if ($logoContents === false) {
                continue;
            }

            return 'data:' . $logoCandidate['mime'] . ';base64,' . base64_encode($logoContents);
        }

        return null;
    }
}
