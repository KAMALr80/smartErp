@extends('layouts.app')

@section('content')
    <div
        style="
    max-width:1100px;
    margin:30px auto;
    background:#ffffff;
    padding:35px;
    border-radius:16px;
    box-shadow:0 15px 35px rgba(0,0,0,0.08);
    font-family:'Segoe UI', Tahoma, sans-serif;
">

        {{-- ================= HEADER ================= --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;">
            <h2 style="font-size:28px;font-weight:800;">
                🧾 Invoice : {{ $sale->invoice_no }}
            </h2>

            <div>
                <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank"
                    style="background:#16a34a;color:#fff;
               padding:10px 18px;border-radius:10px;
               text-decoration:none;font-weight:600;">
                    🖨 Print PDF
                </a>

                <a href="{{ route('sales.index') }}"
                    style="margin-left:10px;background:#374151;color:#fff;
               padding:10px 18px;border-radius:10px;
               text-decoration:none;font-weight:600;">
                    ⬅ Back
                </a>
            </div>
        </div>

        {{-- ================= CUSTOMER INFO ================= --}}
        <div
            style="
        background:#f9fafb;
        padding:20px;
        border-radius:12px;
        margin-bottom:25px;
        display:grid;
        grid-template-columns:repeat(2,1fr);
        gap:15px;
    ">
            <div>
                <b>Customer Name:</b><br>
                {{ $sale->customer->name }}
            </div>

            <div>
                <b>Mobile:</b><br>
                {{ $sale->customer->mobile }}
            </div>

            <div>
                <b>Email:</b><br>
                {{ $sale->customer->email ?? '-' }}
            </div>

            <div>
                <b>Invoice Date:</b><br>
                {{ $sale->sale_date->format('d M Y') }}
            </div>
        </div>

        {{-- ================= ITEMS TABLE ================= --}}
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f3f4f6;">
                    <th style="padding:12px;border:1px solid #e5e7eb;">#</th>
                    <th style="padding:12px;border:1px solid #e5e7eb;">Product</th>
                    <th style="padding:12px;border:1px solid #e5e7eb;">Price</th>
                    <th style="padding:12px;border:1px solid #e5e7eb;">Qty</th>
                    <th style="padding:12px;border:1px solid #e5e7eb;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $index => $item)
                    <tr>
                        <td style="padding:12px;border:1px solid #e5e7eb;text-align:center;">
                            {{ $index + 1 }}
                        </td>
                        <td style="padding:12px;border:1px solid #e5e7eb;">
                            {{ $item->product->name }}
                        </td>
                        <td style="padding:12px;border:1px solid #e5e7eb;">
                            ₹ {{ number_format($item->price, 2) }}
                        </td>
                        <td style="padding:12px;border:1px solid #e5e7eb;text-align:center;">
                            {{ $item->quantity }}
                        </td>
                        <td style="padding:12px;border:1px solid #e5e7eb;">
                            ₹ {{ number_format($item->total, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ================= TOTALS ================= --}}
        <div
            style="
        margin-top:30px;
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
    ">
            <div></div>

            <div style="background:#f9fafb;padding:20px;border-radius:12px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span>Sub Total</span>
                    <b>₹ {{ number_format($sale->sub_total, 2) }}</b>
                </div>

                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span>Discount</span>
                    <b>₹ {{ number_format($sale->discount, 2) }}</b>
                </div>

                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span>Tax (%)</span>
                    <b>{{ $sale->tax }}%</b>
                </div>

                <hr>

                <div style="display:flex;justify-content:space-between;font-size:18px;">
                    <span><b>Grand Total</b></span>
                    <span><b>₹ {{ number_format($sale->grand_total, 2) }}</b></span>
                </div>
            </div>
        </div>

    </div>
@endsection
