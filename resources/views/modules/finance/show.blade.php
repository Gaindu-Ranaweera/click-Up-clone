<x-app-layout>
    <div class="row d-print-none">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Invoice: {{ $invoice->invoice_number }}</h4>
                <div>
                    <button onclick="window.print()" class="btn btn-primary btn-lg text-white">
                        <i class="mdi mdi-printer"></i> Print / Download PDF
                    </button>
                    <a href="{{ route('finance.index') }}" class="btn btn-light btn-lg border">
                        <i class="mdi mdi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-wrapper">
        <div id="printableInvoice" class="invoice-container">
            <!-- Top Blue Bar -->
            <div class="top-accent-bar"></div>

            <!-- Header Section -->
            <div class="invoice-header">
                <div class="header-left">
                    <h1 class="invoice-title">INVOICE</h1>
                    
                    <div class="company-info-box mt-4">
                        <h5 class="font-weight-bold mb-1">Codezura IT Solutions</h5>
                        <p>235/B2, Rathmeldoniya, Arawwala,</p>
                        <p>Pannipitiya, Colombo.</p>
                        <p>+94 77 57 36857</p>
                        <p>info@codezura.com</p>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="logo-wrapper mb-3">
                        <div class="cz-logo">
                            <span class="cz-text">CZ</span>
                            <div class="cz-waves"></div>
                        </div>
                    </div>
                    
                    <div class="invoice-meta">
                        <div class="meta-item">
                            <span class="meta-label">DATE</span>
                            <span class="meta-value">{{ date('d.m.Y', strtotime($invoice->invoice_date)) }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">INVOICE NO.</span>
                            <span class="meta-value">{{ $invoice->invoice_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bill To Section -->
            <div class="bill-to-section mt-5">
                <h6 class="text-uppercase font-weight-bold mb-2">BILL TO</h6>
                <div class="bill-to-box">
                    <h5 class="font-weight-bold mb-1">{{ $invoice->client->contact_person }}</h5>
                    <p>{{ $invoice->client->company_name }}</p>
                    <p>{{ $invoice->client->address }}</p>
                    <p>{{ $invoice->client->phone }}</p>
                    <p>{{ $invoice->client->email }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="items-section mt-5">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>DESCRIPTION</th>
                            <th width="80">QTY</th>
                            <th width="120">UNIT PRICE</th>
                            <th width="120">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td>1</td>
                                <td>{{ number_format($item->amount, 2) }}</td>
                                <td>{{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Payment Summary -->
            <div class="payment-summary mt-5">
                <h6 class="text-uppercase font-weight-bold mb-3">PAYMENT SUMMARY</h6>
                <p>Total Project Amount: LKR {{ number_format($invoice->total_amount, 2) }}</p>
                @if($invoice->paid_amount > 0)
                    <p>An advance payment of LKR {{ number_format($invoice->paid_amount, 2) }} has been received successfully.</p>
                @endif
                <p>The remaining balance will be settled as per the agreed project terms.</p>
            </div>

            <!-- PAID Stamp -->
            @if($invoice->status == 'paid')
                <div class="paid-stamp">
                    <div class="stamp-inner">
                        <span class="stamp-top">THANK YOU</span>
                        <span class="stamp-main">PAID</span>
                        <span class="stamp-bottom">THANK YOU</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

@push('styles')
    <style>
        .invoice-wrapper {
            --invoice-blue: #00b5ec;
            --invoice-border: #000;
            background-color: #f8f9fa;
            padding: 2.5rem 0;
            display: flex;
            justify-content: center;
        }

        .invoice-container {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            padding: 15mm;
            position: relative;
            border: 1px solid #ddd;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            color: #333;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .top-accent-bar {
            position: absolute;
            top: 20mm;
            right: 15mm;
            width: 300px;
            height: 12px;
            background-color: var(--invoice-blue);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-top: 20mm;
        }

        .invoice-title {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 2px;
            margin: 0;
        }

        .invoice-wrapper .company-info-box, 
        .invoice-wrapper .bill-to-box {
            border: 1px solid #ccc;
            padding: 15px;
            font-size: 14px;
            line-height: 1.4;
            max-width: 350px;
        }
        
        .invoice-wrapper .company-info-box p, 
        .invoice-wrapper .bill-to-box p {
            margin: 0;
        }

        /* Logo Styling */
        .cz-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid var(--invoice-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: #fff;
        }
        
        .cz-text {
            color: #1a4f8a;
            font-size: 32px;
            font-weight: 900;
            z-index: 2;
        }
        
        .cz-waves {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 40%;
            background: rgba(0, 181, 236, 0.2);
            border-top: 2px solid var(--invoice-blue);
        }

        .invoice-meta {
            text-align: center;
            margin-top: 20px;
        }
        
        .meta-item {
            margin-bottom: 5px;
        }
        
        .meta-label {
            display: block;
            font-size: 12px;
            font-weight: bold;
            color: #666;
            margin-bottom: 2px;
            border-bottom: 2px solid var(--invoice-blue);
            width: fit-content;
            margin: 0 auto;
        }
        
        .meta-value {
            font-size: 14px;
            font-weight: bold;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        .invoice-table th {
            background-color: var(--invoice-blue);
            color: #fff !important;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #fff;
        }
        
        .invoice-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        
        .invoice-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .payment-summary {
            font-size: 14px;
            line-height: 1.6;
        }

        /* Stamp Styling */
        .paid-stamp {
            position: absolute;
            bottom: 30mm;
            right: 30mm;
            width: 150px;
            height: 150px;
            border: 5px dashed #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(-15deg);
            opacity: 0.8;
            color: #28a745;
            font-family: 'Arial', sans-serif;
        }
        
        .stamp-inner {
            text-align: center;
        }
        
        .stamp-top, .stamp-bottom {
            display: block;
            font-size: 12px;
            font-weight: bold;
        }
        
        .stamp-main {
            display: block;
            font-size: 38px;
            font-weight: 900;
            line-height: 1;
            margin: 5px 0;
            border-top: 3px solid #28a745;
            border-bottom: 3px solid #28a745;
        }

        @media print {
            body {
                background: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .invoice-wrapper {
                padding: 0 !important;
                background: none !important;
                display: block !important;
            }
            .invoice-container {
                width: 210mm;
                height: 297mm;
                border: 1px solid #000 !important;
                margin: 0 !important;
                box-shadow: none !important;
                padding: 15mm !important;
            }
            .d-print-none {
                display: none !important;
            }
            .main-panel, .content-wrapper, .container-fluid, .page-body-wrapper {
                padding: 0 !important;
                margin: 0 !important;
            }
            /* Explicitly hide dashboard elements in print */
            nav.navbar, 
            nav.sidebar, 
            footer.footer {
                display: none !important;
            }
        }
    </style>
@endpush

</x-app-layout>
