<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Receipt <?= esc($sale->invoice_no) ?></title>
  <style>
    @page {
      margin: 0;
    }
    body {
      font-family: 'Courier New', Courier, monospace;
      font-size: 13px;
      line-height: 1.25;
      color: #000;
      background: #fff;
      width: 78mm;
      margin: 0 auto;
      padding: 10px;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .font-bold { font-weight: bold; }
    .divider {
      border-top: 1px dashed #000;
      margin: 6px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 2px 0;
    }
    .no-print {
      margin-bottom: 10px;
      text-align: center;
    }
    @media print {
      .no-print { display: none; }
      body { width: 100%; }
    }
  </style>
</head>
<body onload="window.print()">

<div class="no-print">
  <button onclick="window.print()" style="padding: 6px 16px; font-weight: bold; cursor: pointer;">Print Receipt</button>
  <button onclick="window.close()" style="padding: 6px 16px; margin-left: 6px;">Close</button>
</div>

<div class="text-center">
  <div style="font-size: 18px; font-weight: bold;">EPOS RETAIL</div>
  <div><?= esc($sale->store_name) ?></div>
  <?php if (!empty($sale->store_address)): ?>
    <div><?= esc($sale->store_address) ?></div>
  <?php endif; ?>
  <?php if (!empty($sale->store_phone)): ?>
    <div>Tel: <?= esc($sale->store_phone) ?></div>
  <?php endif; ?>
</div>

<div class="divider"></div>

<div>
  <div><b>Inv #:</b> <?= esc($sale->invoice_no) ?></div>
  <div><b>Date:</b> <?= date('d-M-Y h:i A', strtotime($sale->sale_date)) ?></div>
  <div><b>Cashier:</b> <?= esc($sale->cashier_name) ?></div>
  <?php if (!empty($sale->customer_name)): ?>
    <div><b>Customer:</b> <?= esc($sale->customer_name) ?> (<?= esc($sale->customer_phone ?? '') ?>)</div>
  <?php endif; ?>
</div>

<div class="divider"></div>

<table>
  <thead>
    <tr>
      <th align="left">Item</th>
      <th align="center">Qty</th>
      <th align="right">Price</th>
      <th align="right">Total</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($sale->items as $it): ?>
      <tr>
        <td colspan="4" class="font-bold"><?= esc($it->product_name) ?></td>
      </tr>
      <tr>
        <td style="font-size: 11px; color: #444;"><?= esc($it->product_sku) ?></td>
        <td align="center"><?= number_format($it->qty, 1) ?></td>
        <td align="right"><?= number_format($it->unit_price, 2) ?></td>
        <td align="right" class="font-bold"><?= number_format($it->line_total, 2) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="divider"></div>

<table>
  <tr>
    <td>Subtotal:</td>
    <td align="right">Rs. <?= number_format($sale->subtotal, 2) ?></td>
  </tr>
  <?php if ($sale->discount_amount > 0): ?>
    <tr>
      <td>Discount:</td>
      <td align="right">- Rs. <?= number_format($sale->discount_amount, 2) ?></td>
    </tr>
  <?php endif; ?>
  <?php if ($sale->loyalty_redeemed > 0): ?>
    <tr>
      <td>Points Redeemed:</td>
      <td align="right">- Rs. <?= number_format($sale->loyalty_redeemed, 2) ?></td>
    </tr>
  <?php endif; ?>
  <?php if ($sale->tax_amount > 0): ?>
    <tr>
      <td>Tax / GST:</td>
      <td align="right">+ Rs. <?= number_format($sale->tax_amount, 2) ?></td>
    </tr>
  <?php endif; ?>
  <tr style="font-size: 15px; font-weight: bold;">
    <td>TOTAL AMOUNT:</td>
    <td align="right">Rs. <?= number_format($sale->total_amount, 2) ?></td>
  </tr>
  <tr>
    <td>Amount Paid:</td>
    <td align="right">Rs. <?= number_format($sale->paid_amount, 2) ?></td>
  </tr>
  <tr class="font-bold">
    <td>Change Returned:</td>
    <td align="right">Rs. <?= number_format($sale->change_amount, 2) ?></td>
  </tr>
</table>

<?php if (!empty($sale->payments)): ?>
  <div class="divider"></div>
  <div style="font-size: 11px;">
    Payment Method: 
    <?php foreach ($sale->payments as $p): ?>
      <b><?= esc($p->method_name) ?></b> (Rs. <?= number_format($p->amount, 2) ?>)
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="divider"></div>

<div class="text-center" style="margin-top: 8px;">
  <div>Thank you for your business!</div>
  <div style="font-size: 10px; margin-top: 4px;">Software by EPOS Retail Solution</div>
</div>

</body>
</html>
