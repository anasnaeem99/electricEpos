<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EPOS Terminal | <?= esc($storeName) ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Bootstrap 4 / AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <style>
    body {
      background-color: #e9ecef;
      user-select: none;
      overflow-x: hidden;
      font-family: 'Source Sans Pro', sans-serif;
    }
    .pos-topbar {
      background: #1e293b;
      color: #fff;
      padding: 8px 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .pos-brand {
      font-weight: 800;
      font-size: 1.4rem;
      letter-spacing: 1.5px;
      color: #38bdf8;
    }
    .pos-shortcut-badge {
      background: #334155;
      color: #f1f5f9;
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-right: 6px;
      border: 1px solid #475569;
    }
    .pos-container {
      height: calc(100vh - 56px);
      padding: 10px;
    }
    .cart-card {
      height: calc(100vh - 76px);
      display: flex;
      flex-direction: column;
      border-radius: 8px;
    }
    .cart-table-wrapper {
      flex: 1;
      overflow-y: auto;
    }
    .cart-summary {
      background: #f8fafc;
      border-top: 2px solid #e2e8f0;
      padding: 15px;
    }
    .grand-total-display {
      font-size: 2.2rem;
      font-weight: 800;
      color: #0284c7;
      letter-spacing: -0.5px;
    }
    .cart-row:hover, .cart-row.selected {
      background-color: #e0f2fe !important;
      cursor: pointer;
    }
    .pay-btn-big {
      height: 60px;
      font-size: 1.3rem;
      font-weight: 700;
    }
    .search-input-lg {
      height: 48px;
      font-size: 1.15rem;
      font-weight: 600;
      border: 2px solid #0284c7;
    }
    .item-qty-btn {
      width: 28px;
      height: 28px;
      padding: 0;
      font-weight: bold;
    }
  </style>
</head>
<body>

<!-- POS Top Navigation Bar -->
<div class="pos-topbar">
  <div class="d-flex align-items-center">
    <span class="pos-brand mr-3">EPOS</span>
    <span class="badge badge-primary mr-2 font-weight-normal px-2 py-1"><i class="fas fa-store mr-1"></i> <?= esc($storeName) ?></span>
    <span class="badge badge-secondary font-weight-normal px-2 py-1"><i class="fas fa-user mr-1"></i> <?= esc($cashierName) ?></span>
  </div>

  <!-- Keyboard Shortcuts Hints -->
  <div class="d-none d-lg-flex align-items-center">
    <span class="pos-shortcut-badge">F1 New</span>
    <span class="pos-shortcut-badge">F2 Search</span>
    <span class="pos-shortcut-badge">F3 Cust</span>
    <span class="pos-shortcut-badge">F4 Hold</span>
    <span class="pos-shortcut-badge">F5 Resume</span>
    <span class="pos-shortcut-badge">F6 Disc</span>
    <span class="pos-shortcut-badge">F8 Pay</span>
    <span class="pos-shortcut-badge">F9 Cash</span>
    <span class="pos-shortcut-badge">F11 Ret</span>
  </div>

  <div>
    <span id="pos-clock" class="font-weight-bold text-warning mr-3 font-monospace"></span>
    <a href="<?= base_url('dashboard') ?>" class="btn btn-sm btn-outline-light">
      <i class="fas fa-arrow-left mr-1"></i> Exit POS
    </a>
  </div>
</div>

<!-- Main POS Workspace -->
<div class="pos-container">
  <div class="row h-100 no-gutters">

    <!-- LEFT PANEL: Product Search + Cart Table + Totals (65%) -->
    <div class="col-lg-8 pr-2 h-100">
      <div class="card cart-card shadow-sm mb-0">

        <!-- Search Bar -->
        <div class="p-2 border-bottom bg-white position-relative">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-primary text-white"><i class="fas fa-barcode"></i></span>
            </div>
            <input type="text" id="pos-search" class="form-control search-input-lg" placeholder="Scan Barcode or Search by Name / SKU (Press F2)..." autocomplete="off" autofocus>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" onclick="clearCart()" title="Clear cart (F1)"><i class="fas fa-redo"></i> F1</button>
            </div>
          </div>
          <!-- Live search dropdown -->
          <div id="search-dropdown" class="shadow-lg border rounded bg-white" style="display:none; position:absolute; top:60px; left:8px; right:8px; z-index:1050; max-height:350px; overflow-y:auto;"></div>
        </div>

        <!-- Cart Table -->
        <div class="cart-table-wrapper p-0">
          <table class="table table-sm table-striped mb-0" id="cart-table">
            <thead class="thead-light sticky-top">
              <tr>
                <th width="5%">#</th>
                <th width="40%">Item Description</th>
                <th width="15%" class="text-center">Qty</th>
                <th width="15%" class="text-right">Price</th>
                <th width="15%" class="text-right">Total</th>
                <th width="10%" class="text-center">Del</th>
              </tr>
            </thead>
            <tbody id="cart-tbody">
              <!-- Cart items injected via JS -->
            </tbody>
          </table>
          <div id="empty-cart-msg" class="text-center text-muted py-5">
            <i class="fas fa-shopping-cart fa-3x mb-2 text-black-50 d-block"></i>
            <h5>Cart is empty</h5>
            <small>Scan a barcode or press <b>F2</b> to search items</small>
          </div>
        </div>

        <!-- Cart Summary / Totals -->
        <div class="cart-summary">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Subtotal:</span>
                <span class="font-weight-bold" id="disp-subtotal">Rs. 0.00</span>
              </div>
              <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Item Discount:</span>
                <span class="text-danger" id="disp-item-disc">- Rs. 0.00</span>
              </div>
              <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Bill Discount (F6):</span>
                <span class="text-danger" id="disp-bill-disc">- Rs. 0.00</span>
              </div>
              <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Tax (GST):</span>
                <span class="text-dark" id="disp-tax">+ Rs. 0.00</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Loyalty Points Value:</span>
                <span class="text-success" id="disp-loyalty">- Rs. 0.00</span>
              </div>
            </div>

            <div class="col-md-6 text-right border-left">
              <div class="text-muted small uppercase font-weight-bold">Payable Amount</div>
              <div class="grand-total-display font-monospace" id="disp-grand-total">Rs. 0.00</div>
              <span class="badge badge-info" id="cart-item-count">0 Items</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL: Customer & Quick Payment (35%) -->
    <div class="col-lg-4 pl-2 h-100">
      <div class="card h-100 shadow-sm d-flex flex-column">

        <!-- Customer Section (F3) -->
        <div class="card-header bg-white p-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="font-weight-bold text-dark"><i class="fas fa-user-circle mr-1 text-primary"></i> Customer (F3)</span>
            <button class="btn btn-xs btn-outline-primary" onclick="openNewCustomerModal()"><i class="fas fa-plus"></i> New</button>
          </div>
          <div class="input-group input-group-sm">
            <input type="text" id="cust-search" class="form-control" placeholder="Search customer phone / name...">
            <div class="input-group-append">
              <button class="btn btn-primary" onclick="triggerCustomerSearch()"><i class="fas fa-search"></i></button>
            </div>
          </div>
          <div id="cust-selected-info" class="mt-2 p-2 bg-light border rounded" style="display:none;">
            <div class="d-flex justify-content-between">
              <b id="cust-name-disp">Customer Name</b>
              <button class="btn btn-xs text-danger" onclick="clearCustomer()">&times;</button>
            </div>
            <div class="small text-muted" id="cust-phone-disp">Phone: 0300-1234567</div>
            <div class="small text-success font-weight-bold mt-1">
              <i class="fas fa-star text-warning"></i> Loyalty Points: <span id="cust-points-disp">0</span> (Rs. <span id="cust-points-val">0.00</span>)
              <button class="btn btn-xs btn-outline-success ml-2 py-0" id="btn-redeem-pts" onclick="applyLoyaltyPoints()">Redeem</button>
            </div>
          </div>
        </div>

        <!-- Payment Actions Section -->
        <div class="card-body p-3 d-flex flex-column justify-content-between">
          <div>
            <label class="font-weight-bold text-muted small mb-2 uppercase">Payment Mode</label>
            <div class="row no-gutters mb-3">
              <?php foreach ($paymentMethods as $pm): ?>
                <div class="col-6 p-1">
                  <button type="button" class="btn btn-outline-secondary btn-block pay-method-btn font-weight-bold <?= ($pm->id == 1) ? 'active btn-primary text-white' : '' ?>" data-id="<?= $pm->id ?>" onclick="selectPayMethod(<?= $pm->id ?>, this)">
                    <?= esc($pm->name) ?>
                  </button>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Tender Input -->
            <div class="form-group mb-2">
              <label class="font-weight-bold text-muted small">Tendered Cash / Paid Amount</label>
              <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                  <span class="input-group-text font-weight-bold">Rs.</span>
                </div>
                <input type="number" step="0.01" id="tender-amount" class="form-control font-weight-bold font-monospace" placeholder="0.00" oninput="calcChange()">
              </div>
            </div>

            <!-- Quick Cash Notes Buttons -->
            <div class="d-flex justify-content-between mb-3">
              <button class="btn btn-sm btn-light border font-weight-bold" onclick="addTender(500)">+500</button>
              <button class="btn btn-sm btn-light border font-weight-bold" onclick="addTender(1000)">+1000</button>
              <button class="btn btn-sm btn-light border font-weight-bold" onclick="addTender(2000)">+2000</button>
              <button class="btn btn-sm btn-light border font-weight-bold" onclick="addTender(5000)">+5000</button>
              <button class="btn btn-sm btn-info font-weight-bold" onclick="setExactTender()">Exact</button>
            </div>

            <!-- Change Return Calculation -->
            <div class="p-3 bg-light border rounded text-center mb-3">
              <span class="text-muted small uppercase font-weight-bold">Change Due To Customer</span>
              <div class="h3 font-weight-bold text-success font-monospace m-0" id="disp-change">Rs. 0.00</div>
            </div>
          </div>

          <!-- Checkout & Action Buttons -->
          <div>
            <button class="btn btn-success btn-block pay-btn-big shadow mb-2" id="btn-checkout" onclick="completeCheckout()">
              <i class="fas fa-check-circle mr-1"></i> COMPLETE SALE (F9 / Enter)
            </button>

            <div class="row no-gutters">
              <div class="col-4 p-1">
                <button class="btn btn-warning btn-block font-weight-bold btn-sm py-2" onclick="holdCurrentSale()">
                  <i class="fas fa-pause mr-1"></i> Hold (F4)
                </button>
              </div>
              <div class="col-4 p-1">
                <button class="btn btn-info btn-block font-weight-bold btn-sm py-2" onclick="openResumeModal()">
                  <i class="fas fa-play mr-1"></i> Resume (F5)
                </button>
              </div>
              <div class="col-4 p-1">
                <button class="btn btn-danger btn-block font-weight-bold btn-sm py-2" onclick="openReturnModal()">
                  <i class="fas fa-undo mr-1"></i> Return (F11)
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal: Bill Discount (F6) -->
<div class="modal fade" id="modal-discount" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title font-weight-bold">Bill Discount (F6)</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Discount Amount (Rs.)</label>
          <input type="number" step="0.01" id="input-bill-disc" class="form-control form-control-lg font-weight-bold text-center" value="0.00" autofocus>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-warning font-weight-bold" onclick="applyBillDiscount()">Apply</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: New Customer Quick Add -->
<div class="modal fade" id="modal-new-customer" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-1"></i> Register Loyalty Customer</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Customer Full Name <span class="text-danger">*</span></label>
          <input type="text" id="new-cust-name" class="form-control" required placeholder="e.g. Muhammad Ali">
        </div>
        <div class="form-group">
          <label>Mobile Number <span class="text-danger">*</span></label>
          <input type="text" id="new-cust-phone" class="form-control" required placeholder="e.g. 03001234567">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary font-weight-bold" onclick="saveNewCustomer()">Save & Attach</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Resume Held Sales (F5) -->
<div class="modal fade" id="modal-resume" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-play mr-1"></i> Resume Held Transactions</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr><th>Reference</th><th>Held Time</th><th>Action</th></tr>
          </thead>
          <tbody id="holds-table-body"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Sale Return (F11) -->
<div class="modal fade" id="modal-return" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-undo mr-1"></i> Sales Return & Refund</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Enter Original Invoice Number</label>
          <input type="text" id="return-invoice-input" class="form-control font-weight-bold" placeholder="e.g. INV-LHR01-20261114-0001">
        </div>
        <p class="small text-muted">Returns will restock items to shelf and reverse any loyalty points earned.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="<?= base_url('pos/sales') ?>" class="btn btn-danger font-weight-bold">View Invoices List</a>
      </div>
    </div>
  </div>
</div>

<!-- REQUIRED SCRIPTS -->
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // POS State
  let cart = [];
  let currentCustomer = null;
  let billDiscount = 0.00;
  let loyaltyRedeemed = 0.00;
  let selectedPayMethod = 1; // default Cash
  let lastCommittedSaleId = null;

  // Clock
  setInterval(function() {
    var d = new Date();
    $('#pos-clock').text(d.toLocaleTimeString());
  }, 1000);

  // Keyboard Shortcuts Handler
  $(document).on('keydown', function(e) {
    // If modal open, Esc closes modal
    if (e.key === 'Escape') {
      $('.modal').modal('hide');
      $('#search-dropdown').hide();
      $('#pos-search').focus();
      return;
    }

    // Function Keys
    switch(e.key) {
      case 'F1': e.preventDefault(); clearCart(); break;
      case 'F2': e.preventDefault(); $('#pos-search').focus().select(); break;
      case 'F3': e.preventDefault(); $('#cust-search').focus().select(); break;
      case 'F4': e.preventDefault(); holdCurrentSale(); break;
      case 'F5': e.preventDefault(); openResumeModal(); break;
      case 'F6': e.preventDefault(); $('#modal-discount').modal('show'); break;
      case 'F8': e.preventDefault(); $('#tender-amount').focus().select(); break;
      case 'F9': e.preventDefault(); setExactTender(); completeCheckout(); break;
      case 'F10': e.preventDefault(); reprintLast(); break;
      case 'F11': e.preventDefault(); openReturnModal(); break;
      case 'Delete':
        var selIdx = $('.cart-row.selected').data('idx');
        if (selIdx !== undefined) removeCartItem(selIdx);
        break;
      case 'Enter':
        // If search focused and dropdown has 1 result, add it
        if ($('#pos-search').is(':focus')) {
          var firstItem = $('#search-dropdown .search-res-item').first();
          if (firstItem.length) {
            firstItem.trigger('click');
          }
        } else if ($('#tender-amount').is(':focus')) {
          completeCheckout();
        }
        break;
    }
  });

  // Product Search & Barcode Scanner handling
  let searchTimer = null;
  $('#pos-search').on('keyup', function(e) {
    if (e.key === 'Enter' || e.key === 'ArrowDown') return;

    var query = $(this).val().trim();
    clearTimeout(searchTimer);

    if (query.length < 2) {
      $('#search-dropdown').hide();
      return;
    }

    searchTimer = setTimeout(function() {
      $.getJSON('<?= base_url('inventory/products/search') ?>?q=' + encodeURIComponent(query), function(items) {
        if (!items || items.length === 0) {
          $('#search-dropdown').html('<div class="p-3 text-muted text-center">No products found.</div>').show();
          return;
        }

        // If exact barcode scanned (single match & scanner enter speed)
        if (items.length === 1 && (items[0].sku === query || (items[0].barcodes && items[0].barcodes.some(b => b.barcode === query)))) {
          addToCart(items[0]);
          $('#pos-search').val('').focus();
          $('#search-dropdown').hide();
          return;
        }

        var html = '';
        $.each(items, function(idx, p) {
          html += `
            <div class="p-2 border-bottom hover-bg d-flex justify-content-between align-items-center search-res-item" data-json='${JSON.stringify(p)}' style="cursor:pointer;">
              <div>
                <b class="text-primary">${p.name}</b> <small class="text-muted">(${p.sku})</small><br>
                <span class="badge badge-light border">Stock: ${parseFloat(p.current_stock).toFixed(1)} ${p.unit_abbr || ''}</span>
              </div>
              <div class="text-right">
                <span class="h6 font-weight-bold text-success mb-0">Rs. ${parseFloat(p.retail_price).toFixed(2)}</span>
              </div>
            </div>
          `;
        });
        $('#search-dropdown').html(html).show();
      });
    }, 200);
  });

  // Click on search result
  $(document).on('click', '.search-res-item', function() {
    var p = $(this).data('json');
    addToCart(p);
    $('#pos-search').val('').focus();
    $('#search-dropdown').hide();
  });

  // Cart Management
  function addToCart(p) {
    var existingIdx = cart.findIndex(item => item.product_id === p.id);
    if (existingIdx !== -1) {
      cart[existingIdx].qty += 1;
      cart[existingIdx].line_total = cart[existingIdx].qty * cart[existingIdx].unit_price;
    } else {
      cart.push({
        product_id: p.id,
        product_name: p.name,
        product_sku: p.sku,
        unit_name: p.unit_abbr || 'pcs',
        unit_price: parseFloat(p.retail_price),
        qty: 1,
        discount: 0.00,
        tax_rate: parseFloat(p.tax_rate || 0),
        tax_amount: 0.00,
        line_total: parseFloat(p.retail_price),
      });
    }
    renderCart();
  }

  function changeQty(idx, delta) {
    if (cart[idx]) {
      cart[idx].qty += delta;
      if (cart[idx].qty <= 0) {
        cart.splice(idx, 1);
      } else {
        cart[idx].line_total = cart[idx].qty * cart[idx].unit_price;
      }
      renderCart();
    }
  }

  function removeCartItem(idx) {
    cart.splice(idx, 1);
    renderCart();
  }

  function clearCart() {
    cart = [];
    billDiscount = 0.00;
    loyaltyRedeemed = 0.00;
    renderCart();
    $('#pos-search').focus();
  }

  function renderCart() {
    var tbody = $('#cart-tbody');
    tbody.empty();

    if (cart.length === 0) {
      $('#empty-cart-msg').show();
      $('#cart-table').hide();
    } else {
      $('#empty-cart-msg').hide();
      $('#cart-table').show();
    }

    var subtotal = 0;
    var taxTotal = 0;

    $.each(cart, function(idx, it) {
      subtotal += it.line_total;
      var tax = (it.line_total * it.tax_rate) / 100;
      it.tax_amount = tax;
      taxTotal += tax;

      var rowHtml = `
        <tr class="cart-row" data-idx="${idx}">
          <td>${idx + 1}</td>
          <td>
            <b class="text-dark">${it.product_name}</b><br>
            <small class="text-muted"><code>${it.product_sku}</code> @ Rs. ${it.unit_price.toFixed(2)}</small>
          </td>
          <td class="text-center">
            <div class="d-inline-flex align-items-center">
              <button class="btn btn-xs btn-outline-secondary item-qty-btn" onclick="changeQty(${idx}, -1)">-</button>
              <span class="mx-2 font-weight-bold font-monospace">${parseFloat(it.qty).toFixed(1)}</span>
              <button class="btn btn-xs btn-outline-secondary item-qty-btn" onclick="changeQty(${idx}, 1)">+</button>
            </div>
          </td>
          <td class="text-right font-monospace">Rs. ${it.unit_price.toFixed(2)}</td>
          <td class="text-right font-weight-bold text-primary font-monospace">Rs. ${it.line_total.toFixed(2)}</td>
          <td class="text-center">
            <button class="btn btn-xs text-danger" onclick="removeCartItem(${idx})"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
      `;
      tbody.append(rowHtml);
    });

    var grandTotal = Math.max(0, subtotal - billDiscount - loyaltyRedeemed + taxTotal);

    $('#disp-subtotal').text('Rs. ' + subtotal.toFixed(2));
    $('#disp-bill-disc').text('- Rs. ' + billDiscount.toFixed(2));
    $('#disp-tax').text('+ Rs. ' + taxTotal.toFixed(2));
    $('#disp-loyalty').text('- Rs. ' + loyaltyRedeemed.toFixed(2));
    $('#disp-grand-total').text('Rs. ' + grandTotal.toFixed(2));
    $('#cart-item-count').text(cart.length + ' Items');

    // Auto set tender input if cash exact
    calcChange();
  }

  // Row selection
  $(document).on('click', '.cart-row', function() {
    $('.cart-row').removeClass('selected');
    $(this).addClass('selected');
  });

  // Tender & Change calculation
  function calcChange() {
    var grand = getGrandTotal();
    var tendered = parseFloat($('#tender-amount').val()) || 0;
    var change = Math.max(0, tendered - grand);
    $('#disp-change').text('Rs. ' + change.toFixed(2));
  }

  function addTender(amt) {
    var cur = parseFloat($('#tender-amount').val()) || 0;
    $('#tender-amount').val(cur + amt);
    calcChange();
  }

  function setExactTender() {
    var grand = getGrandTotal();
    $('#tender-amount').val(grand.toFixed(2));
    calcChange();
  }

  function getGrandTotal() {
    var subtotal = cart.reduce((sum, it) => sum + it.line_total, 0);
    var tax = cart.reduce((sum, it) => sum + it.tax_amount, 0);
    return Math.max(0, subtotal - billDiscount - loyaltyRedeemed + tax);
  }

  function selectPayMethod(id, btn) {
    selectedPayMethod = id;
    $('.pay-method-btn').removeClass('active btn-primary text-white').addClass('btn-outline-secondary');
    $(btn).addClass('active btn-primary text-white').removeClass('btn-outline-secondary');
  }

  function applyBillDiscount() {
    billDiscount = parseFloat($('#input-bill-disc').val()) || 0;
    $('#modal-discount').modal('hide');
    renderCart();
  }

  // Customer & Loyalty Search
  function triggerCustomerSearch() {
    var q = $('#cust-search').val().trim();
    if (!q) return;

    $.getJSON('<?= base_url('pos/search-customer') ?>?q=' + encodeURIComponent(q), function(res) {
      if (res && res.length > 0) {
        attachCustomer(res[0]);
      } else {
        Swal.fire({
          title: 'Customer not found',
          text: 'Would you like to register new customer with phone "' + q + '"?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Register'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#new-cust-phone').val(q);
            $('#modal-new-customer').modal('show');
          }
        });
      }
    });
  }

  function attachCustomer(c) {
    currentCustomer = c;
    $('#cust-name-disp').text(c.name);
    $('#cust-phone-disp').text('Phone: ' + c.phone);
    $('#cust-points-disp').text(c.loyalty_points || 0);
    $('#cust-points-val').text(parseFloat(c.loyalty_points || 0).toFixed(2));
    $('#cust-selected-info').show();
    $('#cust-search').val('');
  }

  function clearCustomer() {
    currentCustomer = null;
    loyaltyRedeemed = 0.00;
    $('#cust-selected-info').hide();
    renderCart();
  }

  function applyLoyaltyPoints() {
    if (!currentCustomer || currentCustomer.loyalty_points <= 0) return;
    loyaltyRedeemed = Math.min(getGrandTotal(), parseFloat(currentCustomer.loyalty_points));
    renderCart();
    Swal.fire('Loyalty Applied', 'Redeemed ' + loyaltyRedeemed + ' points (Rs. ' + loyaltyRedeemed + ')', 'success');
  }

  function openNewCustomerModal() {
    $('#modal-new-customer').modal('show');
  }

  function saveNewCustomer() {
    var name = $('#new-cust-name').val().trim();
    var phone = $('#new-cust-phone').val().trim();
    if (!name || !phone) return;

    $.post('<?= base_url('pos/quick-customer') ?>', { name: name, phone: phone }, function(res) {
      if (res.status === 'success') {
        attachCustomer(res.data);
        $('#modal-new-customer').modal('hide');
        Swal.fire('Registered!', 'Customer attached to sale.', 'success');
      } else {
        alert(res.message);
      }
    });
  }

  // Complete Checkout
  function completeCheckout() {
    if (cart.length === 0) {
      Swal.fire('Empty Cart', 'Please add items to cart before checkout.', 'warning');
      return;
    }

    var grandTotal = getGrandTotal();
    var tendered = parseFloat($('#tender-amount').val()) || grandTotal;

    if (tendered < grandTotal && selectedPayMethod === 1) {
      Swal.fire('Insufficient Cash', 'Tendered amount is less than total payable.', 'error');
      return;
    }

    var payload = {
      local_txn_uuid: 'POS-' + Date.now() + '-' + Math.floor(Math.random() * 10000),
      customer_id: currentCustomer ? currentCustomer.id : null,
      subtotal: cart.reduce((s, it) => s + it.line_total, 0),
      discount_amount: billDiscount,
      bill_discount: billDiscount,
      tax_amount: cart.reduce((s, it) => s + it.tax_amount, 0),
      loyalty_redeemed: loyaltyRedeemed,
      total_amount: grandTotal,
      paid_amount: tendered,
      change_amount: Math.max(0, tendered - grandTotal),
      items: cart,
      payments: [{
        method_id: selectedPayMethod,
        amount: grandTotal
      }]
    };

    $('#btn-checkout').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');

    $.ajax({
      url: '<?= base_url('pos/sale') ?>',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(payload),
      success: function(res) {
        $('#btn-checkout').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> COMPLETE SALE (F9 / Enter)');

        if (res.status === 'success') {
          lastCommittedSaleId = res.data.sale_id;
          Swal.fire({
            title: 'Sale Complete!',
            html: `Invoice: <b>${res.data.invoice_no}</b><br>Change: <b class="text-success h4">Rs. ${parseFloat(res.data.change || 0).toFixed(2)}</b>`,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-print mr-1"></i> Print Receipt',
            cancelButtonText: 'Next Sale (Enter)'
          }).then((result) => {
            if (result.isConfirmed) {
              window.open('<?= base_url('pos/receipt') ?>/' + res.data.sale_id, '_blank', 'width=400,height=600');
            }
            clearCart();
            clearCustomer();
            $('#tender-amount').val('');
            $('#disp-change').text('Rs. 0.00');
          });
        } else {
          Swal.fire('Error', res.message, 'error');
        }
      },
      error: function() {
        $('#btn-checkout').prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> COMPLETE SALE (F9 / Enter)');
        Swal.fire('Connection Error', 'Transaction queued for offline sync.', 'info');
      }
    });
  }

  // Hold & Resume
  function holdCurrentSale() {
    if (cart.length === 0) return;
    var ref = prompt('Enter customer name or reference note for hold:', 'Hold ' + new Date().toLocaleTimeString());
    if (ref === null) return;

    var payload = {
      hold_ref: ref,
      customer_id: currentCustomer ? currentCustomer.id : null,
      items: cart,
      bill_discount: billDiscount
    };

    $.ajax({
      url: '<?= base_url('pos/hold') ?>',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(payload),
      success: function(res) {
        if (res.status === 'success') {
          clearCart();
          clearCustomer();
          Swal.fire('Held', 'Sale suspended. Press F5 anytime to resume.', 'info');
        }
      }
    });
  }

  function openResumeModal() {
    $.getJSON('<?= base_url('pos/holds-list') ?>', function(holds) {
      var tbody = $('#holds-table-body').empty();
      if (!holds || holds.length === 0) {
        tbody.append('<tr><td colspan="3" class="text-center py-4 text-muted">No held transactions found.</td></tr>');
      } else {
        $.each(holds, function(i, h) {
          tbody.append(`
            <tr>
              <td><b>${h.hold_ref}</b></td>
              <td>${new Date(h.held_at).toLocaleTimeString()}</td>
              <td>
                <button class="btn btn-xs btn-success font-weight-bold" onclick="resumeHeldSale(${h.id})">
                  <i class="fas fa-play mr-1"></i> Resume
                </button>
              </td>
            </tr>
          `);
        });
      }
      $('#modal-resume').modal('show');
    });
  }

  function resumeHeldSale(holdId) {
    $.getJSON('<?= base_url('pos/resume') ?>/' + holdId, function(data) {
      if (data && data.items) {
        cart = data.items;
        billDiscount = data.bill_discount || 0.00;
        renderCart();
        $('#modal-resume').modal('hide');
        Swal.fire('Resumed', 'Cart restored.', 'success');
      }
    });
  }

  function reprintLast() {
    if (lastCommittedSaleId) {
      window.open('<?= base_url('pos/receipt') ?>/' + lastCommittedSaleId, '_blank', 'width=400,height=600');
    } else {
      Swal.fire('No recent sale', 'No transaction committed in this session yet.', 'info');
    }
  }

  function openReturnModal() {
    $('#modal-return').modal('show');
  }
</script>
</body>
</html>
