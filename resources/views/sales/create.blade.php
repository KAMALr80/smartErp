@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div
        style="
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 40px;
            border-radius: 20px;
            max-width: 1300px;
            margin: 30px auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            border: 1px solid #e5e7eb;
        ">

        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
            <div
                style="
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25);
            ">
                <span style="font-size: 28px; color: white;">🧾</span>
            </div>
            <div>
                <h1
                    style="
                    font-size: 32px;
                    font-weight: 800;
                    margin: 0;
                    color: #1e293b;
                    letter-spacing: -0.5px;
                ">
                    Create New Invoice</h1>
                <p style="color: #64748b; margin: 5px 0 0; font-size: 15px;">
                    Create and manage sales invoices efficiently
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('sales.store') }}" onsubmit="handleSubmit(this)">
            @csrf
            <input type="hidden" name="invoice_token" value="{{ Str::uuid() }}">

            {{-- CUSTOMER + SEARCH SECTION --}}
            <div
                style="
                background: white;
                padding: 25px;
                border-radius: 16px;
                margin-bottom: 30px;
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            ">
                <h3
                    style="
                    font-size: 18px;
                    font-weight: 700;
                    color: #374151;
                    margin: 0 0 20px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                ">
                    <span
                        style="
                        display: inline-flex;
                        width: 24px;
                        height: 24px;
                        background: #3b82f6;
                        color: white;
                        border-radius: 6px;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                    ">1</span>
                    Customer & Products
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    {{-- Customer Selection --}}
                    <div>
                        <label
                            style="
                            display: block;
                            color: #374151;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Select
                            Customer</label>
                        <div style="display: flex; gap: 12px;">
                            <select id="customerSelect" name="customer_id"
                                style="
                                flex: 1;
                                padding: 12px 16px;
                                border-radius: 12px;
                                border: 1.5px solid #d1d5db;
                                background: white;
                                font-size: 15px;
                                color: #374151;
                                transition: all 0.2s;
                                outline: none;
                                cursor: pointer;
                            "
                                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                                <option value="" style="color: #9ca3af;">Choose Customer...</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}" style="color: #374151;">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="openCustomerModal()"
                                style="
                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                color: white;
                                border: none;
                                padding: 0 24px;
                                border-radius: 12px;
                                font-weight: 600;
                                font-size: 14px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 8px;
                                white-space: nowrap;
                                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
                                transition: all 0.2s;
                            "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(16, 185, 129, 0.3)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.25)'">
                                <span style="font-size: 16px;">+</span>
                                Add Customer
                            </button>
                        </div>
                    </div>

                    {{-- Product Search --}}
                    <div>
                        <label
                            style="
                            display: block;
                            color: #374151;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Search
                            Products</label>
                        <div style="position: relative;">
                            <input type="text" id="productSearch" disabled placeholder="Type product name to search..."
                                style="
                                    width: 100%;
                                    padding: 12px 16px;
                                    padding-right: 40px;
                                    border-radius: 12px;
                                    border: 1.5px solid #d1d5db;
                                    background: white;
                                    font-size: 15px;
                                    color: #374151;
                                    transition: all 0.2s;
                                    outline: none;
                                "
                                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                            <span
                                style="
                                position: absolute;
                                right: 16px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #9ca3af;
                                font-size: 18px;
                            ">🔍</span>
                            <div id="productResults"
                                style="
                                display: none;
                                position: absolute;
                                top: calc(100% + 8px);
                                width: 100%;
                                background: white;
                                border: 1.5px solid #e5e7eb;
                                border-radius: 12px;
                                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                                max-height: 300px;
                                overflow-y: auto;
                                z-index: 1000;
                            ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ITEMS TABLE --}}
            <div
                style="
                background: white;
                padding: 25px;
                border-radius: 16px;
                margin-bottom: 30px;
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                overflow: hidden;
            ">
                <h3
                    style="
                    font-size: 18px;
                    font-weight: 700;
                    color: #374151;
                    margin: 0 0 20px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                ">
                    <span
                        style="
                        display: inline-flex;
                        width: 24px;
                        height: 24px;
                        background: #f59e0b;
                        color: white;
                        border-radius: 6px;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                    ">2</span>
                    Invoice Items
                </h3>

                <div style="overflow-x: auto; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                        <thead>
                            <tr
                                style="
                                background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
                                color: #1e40af;
                                border-bottom: 2px solid #dbeafe;
                            ">
                                <th
                                    style="
                                    padding: 16px 20px;
                                    text-align: left;
                                    font-weight: 700;
                                    font-size: 14px;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    color: #374151;
                                ">
                                    Product</th>
                                <th
                                    style="
                                    padding: 16px 20px;
                                    text-align: left;
                                    font-weight: 700;
                                    font-size: 14px;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    color: #374151;
                                    width: 150px;
                                ">
                                    Price (₹)</th>
                                <th
                                    style="
                                    padding: 16px 20px;
                                    text-align: left;
                                    font-weight: 700;
                                    font-size: 14px;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    color: #374151;
                                    width: 120px;
                                ">
                                    Quantity</th>
                                <th
                                    style="
                                    padding: 16px 20px;
                                    text-align: left;
                                    font-weight: 700;
                                    font-size: 14px;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    color: #374151;
                                    width: 150px;
                                ">
                                    Total (₹)</th>
                                <th
                                    style="
                                    padding: 16px 20px;
                                    text-align: left;
                                    font-weight: 700;
                                    font-size: 14px;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    color: #374151;
                                    width: 100px;
                                ">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTable"
                            style="
                            background: #fafafa;
                            border-bottom: 1px solid #e5e7eb;
                        ">
                            <tr id="emptyState"
                                style="
                                background: #f8fafc;
                                text-align: center;
                                color: #64748b;
                                font-style: italic;
                            ">
                                <td colspan="5" style="padding: 60px 20px;">
                                    <div
                                        style="
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        gap: 12px;
                                        color: #94a3b8;
                                    ">
                                        <span style="font-size: 48px;">📦</span>
                                        <p style="margin: 0; font-size: 15px;">
                                            Search and add products to start creating your invoice
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOTALS SECTION --}}
            <div
                style="
                background: white;
                padding: 25px;
                border-radius: 16px;
                margin-bottom: 30px;
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            ">
                <h3
                    style="
                    font-size: 18px;
                    font-weight: 700;
                    color: #374151;
                    margin: 0 0 20px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                ">
                    <span
                        style="
                        display: inline-flex;
                        width: 24px;
                        height: 24px;
                        background: #10b981;
                        color: white;
                        border-radius: 6px;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;
                    ">3</span>
                    Invoice Summary
                </h3>

                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    {{-- Sub Total --}}
                    <div>
                        <label
                            style="
                            display: block;
                            color: #64748b;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Sub
                            Total</label>
                        <div style="position: relative;">
                            <span
                                style="
                                position: absolute;
                                left: 16px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #374151;
                                font-weight: 600;
                                font-size: 18px;
                            ">₹</span>
                            <input id="sub_total" name="sub_total" readonly value="0.00"
                                style="
                                width: 100%;
                                padding: 14px 16px 14px 42px;
                                border-radius: 12px;
                                border: 1.5px solid #e5e7eb;
                                background: #f8fafc;
                                font-size: 18px;
                                font-weight: 700;
                                color: #374151;
                                text-align: right;
                                outline: none;
                            ">
                        </div>
                    </div>

                    {{-- Discount --}}
                    <div>
                        <label
                            style="
                            display: block;
                            color: #64748b;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Discount
                            (₹)</label>
                        <div style="position: relative;">
                            <span
                                style="
                                position: absolute;
                                left: 16px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #374151;
                                font-weight: 600;
                                font-size: 18px;
                            ">₹</span>
                            <input id="discount" name="discount" value="0" oninput="calculate()"
                                style="
                                width: 100%;
                                padding: 14px 16px 14px 42px;
                                border-radius: 12px;
                                border: 1.5px solid #e5e7eb;
                                background: white;
                                font-size: 16px;
                                color: #374151;
                                text-align: right;
                                outline: none;
                                transition: all 0.2s;
                            "
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    {{-- Tax --}}
                    <div>
                        <label
                            style="
                            display: block;
                            color: #64748b;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Tax
                            (%)</label>
                        <div style="position: relative;">
                            <span
                                style="
                                position: absolute;
                                left: 16px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #374151;
                                font-weight: 600;
                                font-size: 18px;
                            ">%</span>
                            <input id="tax" name="tax" value="0" oninput="calculate()"
                                style="
                                width: 100%;
                                padding: 14px 16px 14px 42px;
                                border-radius: 12px;
                                border: 1.5px solid #e5e7eb;
                                background: white;
                                font-size: 16px;
                                color: #374151;
                                text-align: right;
                                outline: none;
                                transition: all 0.2s;
                            "
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    {{-- Grand Total --}}
                    <div>
                        <label
                            style="
                            display: block;
                            color: #1e293b;
                            font-weight: 700;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Grand
                            Total</label>
                        <div style="position: relative;">
                            <span
                                style="
                                position: absolute;
                                left: 16px;
                                top: 50%;
                                transform: translateY(-50%);
                                color: #1e293b;
                                font-weight: 700;
                                font-size: 18px;
                            ">₹</span>
                            <input id="grand_total" name="grand_total" readonly value="0.00"
                                style="
                                width: 100%;
                                padding: 14px 16px 14px 42px;
                                border-radius: 12px;
                                border: 2px solid #1e293b;
                                background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                                font-size: 20px;
                                font-weight: 800;
                                color: #1e293b;
                                text-align: right;
                                outline: none;
                                box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
                            ">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTON --}}
            <div style="text-align: right;">
                <button type="submit" id="saveBtn"
                    style="
                    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                    color: white;
                    border: none;
                    padding: 18px 40px;
                    border-radius: 14px;
                    font-weight: 700;
                    font-size: 16px;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 12px;
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                "
                    onmouseover="
                    this.style.transform='translateY(-3px)';
                    this.style.boxShadow='0 12px 25px rgba(0, 0, 0, 0.25)';
                "
                    onmouseout="
                    this.style.transform='translateY(0)';
                    this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.2)';
                ">
                    <span
                        style="
                        background: rgba(255, 255, 255, 0.2);
                        width: 36px;
                        height: 36px;
                        border-radius: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 18px;
                    ">💾</span>
                    Save & Generate Invoice
                </button>
            </div>
        </form>
    </div>

    {{-- ================= CUSTOMER MODAL ================= --}}
    <div id="customerModal"
        style="
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    ">
        <div
            style="
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
            border: 1px solid #e5e7eb;
        ">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 25px;">
                <div
                    style="
                    width: 48px;
                    height: 48px;
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <span style="font-size: 24px; color: white;">👤</span>
                </div>
                <div>
                    <h3
                        style="
                        font-size: 22px;
                        font-weight: 700;
                        color: #1e293b;
                        margin: 0;
                        letter-spacing: -0.5px;
                    ">
                        Add New Customer</h3>
                    <p style="color: #64748b; margin: 4px 0 0; font-size: 14px;">
                        Fill customer details below
                    </p>
                </div>
            </div>

            <div style="display: grid; gap: 16px;">
                <div>
                    <label
                        style="
                        display: block;
                        color: #374151;
                        font-weight: 600;
                        margin-bottom: 8px;
                        font-size: 14px;
                    ">Full
                        Name <span style="color: #ef4444;">*</span></label>
                    <input id="c_name" placeholder="Enter customer name"
                        style="
                        width: 100%;
                        padding: 14px 16px;
                        border-radius: 12px;
                        border: 1.5px solid #e5e7eb;
                        background: white;
                        font-size: 15px;
                        color: #374151;
                        outline: none;
                        transition: all 0.2s;
                    "
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label
                            style="
                            display: block;
                            color: #374151;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Mobile
                            <span style="color: #ef4444;">*</span></label>
                        <input id="c_mobile" placeholder="Enter mobile number"
                            style="
                            width: 100%;
                            padding: 14px 16px;
                            border-radius: 12px;
                            border: 1.5px solid #e5e7eb;
                            background: white;
                            font-size: 15px;
                            color: #374151;
                            outline: none;
                            transition: all 0.2s;
                        "
                            onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label
                            style="
                            display: block;
                            color: #374151;
                            font-weight: 600;
                            margin-bottom: 8px;
                            font-size: 14px;
                        ">Email</label>
                        <input id="c_email" placeholder="Enter email address"
                            style="
                            width: 100%;
                            padding: 14px 16px;
                            border-radius: 12px;
                            border: 1.5px solid #e5e7eb;
                            background: white;
                            font-size: 15px;
                            color: #374151;
                            outline: none;
                            transition: all 0.2s;
                        "
                            onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div>
                    <label
                        style="
                        display: block;
                        color: #374151;
                        font-weight: 600;
                        margin-bottom: 8px;
                        font-size: 14px;
                    ">Address</label>
                    <textarea id="c_address" placeholder="Enter customer address" rows="3"
                        style="
                        width: 100%;
                        padding: 14px 16px;
                        border-radius: 12px;
                        border: 1.5px solid #e5e7eb;
                        background: white;
                        font-size: 15px;
                        color: #374151;
                        outline: none;
                        resize: vertical;
                        transition: all 0.2s;
                        font-family: inherit;
                    "
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"></textarea>
                </div>
            </div>

            <div
                style="
                display: flex;
                gap: 12px;
                margin-top: 30px;
                padding-top: 25px;
                border-top: 1px solid #e5e7eb;
                justify-content: flex-end;
            ">
                <button onclick="closeCustomerModal()"
                    style="
                    background: #f3f4f6;
                    color: #374151;
                    border: 1.5px solid #e5e7eb;
                    padding: 12px 24px;
                    border-radius: 10px;
                    font-weight: 600;
                    font-size: 15px;
                    cursor: pointer;
                    transition: all 0.2s;
                "
                    onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    Cancel
                </button>
                <button onclick="saveCustomer()" id="saveCustomerBtn"
                    style="
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                    border: none;
                    padding: 12px 28px;
                    border-radius: 10px;
                    font-weight: 600;
                    font-size: 15px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
                    transition: all 0.2s;
                "
                    onmouseover="
                    this.style.transform='translateY(-2px)';
                    this.style.boxShadow='0 6px 16px rgba(16, 185, 129, 0.3)';
                "
                    onmouseout="
                    this.style.transform='translateY(0)';
                    this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.25)';
                ">
                    <span style="font-size: 16px;">✓</span>
                    Save Customer
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
        }

        /* Custom scrollbar */
        #productResults::-webkit-scrollbar {
            width: 6px;
        }

        #productResults::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        #productResults::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        #productResults::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    {{-- ================= JS ================= --}}
    <script>
        let products = @json($products);
        let isSavingCustomer = false;

        const customerSelect = document.getElementById('customerSelect');
        const productSearch = document.getElementById('productSearch');
        const productResults = document.getElementById('productResults');
        const itemsTable = document.getElementById('itemsTable');
        const emptyState = document.getElementById('emptyState');
        const saveCustomerBtn = document.getElementById('saveCustomerBtn');

        // Enable product search when customer is selected
        customerSelect.addEventListener('change', () => {
            productSearch.disabled = customerSelect.value === "";
            if (!productSearch.disabled) {
                productSearch.focus();
            }
        });

        // Product search functionality
        productSearch.addEventListener('input', function() {
            let val = this.value.toLowerCase().trim();
            productResults.innerHTML = '';

            if (!val) {
                productResults.style.display = 'none';
                return;
            }

            const filteredProducts = products.filter(p =>
                p.name.toLowerCase().includes(val)
            );

            if (filteredProducts.length === 0) {
                productResults.innerHTML = `
                    <div style="
                        padding: 20px;
                        text-align: center;
                        color: #94a3b8;
                        font-style: italic;
                    ">
                        No products found matching "${val}"
                    </div>
                `;
                productResults.style.display = 'block';
                return;
            }

            filteredProducts.forEach((p, index) => {
                let item = document.createElement('div');
                item.style.cssText = `
                    padding: 14px 16px;
                    cursor: pointer;
                    border-bottom: ${index === filteredProducts.length - 1 ? 'none' : '1px solid #f1f5f9'};
                    transition: all 0.2s;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                `;
                item.innerHTML = `
                    <div>
                        <div style="font-weight: 600; color: #374151; margin-bottom: 4px;">${p.name}</div>
                        <div style="font-size: 13px; color: #64748b;">SKU: ${p.sku || 'N/A'}</div>
                    </div>
                    <div style="
                        background: #10b981;
                        color: white;
                        padding: 6px 12px;
                        border-radius: 8px;
                        font-weight: 700;
                        font-size: 15px;
                    ">
                        ₹${parseFloat(p.price).toFixed(2)}
                    </div>
                `;
                item.onmouseover = () => {
                    item.style.background = '#f8fafc';
                };
                item.onmouseout = () => {
                    item.style.background = 'white';
                };
                item.onclick = () => addProduct(p);
                productResults.appendChild(item);
            });

            productResults.style.display = 'block';
        });

        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!productSearch.contains(e.target) && !productResults.contains(e.target)) {
                productResults.style.display = 'none';
            }
        });

        // Add product to table
        function addProduct(p) {
            productResults.style.display = 'none';
            productSearch.value = '';

            // Hide empty state
            if (emptyState) emptyState.style.display = 'none';

            // Check if product already exists
            let existingRow = null;
            document.querySelectorAll('#itemsTable tr').forEach(row => {
                if (row.dataset.pid == p.id) {
                    existingRow = row;
                }
            });

            if (existingRow) {
                // Increase quantity
                let qtyInput = existingRow.querySelector('.qty');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                // Highlight the row briefly
                existingRow.style.background = '#f0f9ff';
                setTimeout(() => {
                    existingRow.style.background = '';
                }, 300);
            } else {
                // Add new row
                const rowId = `product-row-${p.id}`;
                itemsTable.insertAdjacentHTML('beforeend', `
                    <tr data-pid="${p.id}" id="${rowId}" style="
                        border-bottom: 1px solid #e5e7eb;
                        animation: slideIn 0.3s ease-out;
                        background: #f8fafc;
                    ">
                        <td style="padding: 20px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="
                                    width: 40px;
                                    height: 40px;
                                    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-weight: 600;
                                    font-size: 14px;
                                ">${p.name.charAt(0)}</div>
                                <div>
                                    <div style="font-weight: 600; color: #374151;">${p.name}</div>
                                    <div style="font-size: 13px; color: #64748b;">SKU: ${p.sku || 'N/A'}</div>
                                </div>
                            </div>
                            <input type="hidden" name="items[product_id][]" value="${p.id}">
                        </td>
                        <td style="padding: 20px;">
                            <input name="items[price][]" value="${parseFloat(p.price).toFixed(2)}" oninput="calculate()" style="
                                width: 100%;
                                padding: 10px 12px;
                                border-radius: 8px;
                                border: 1.5px solid #e5e7eb;
                                background: white;
                                font-size: 15px;
                                color: #374151;
                                text-align: right;
                                outline: none;
                                font-weight: 600;
                            " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e5e7eb'">
                        </td>
                        <td style="padding: 20px;">
                            <input type="number" class="qty" name="items[quantity][]" value="1" min="1" oninput="calculate()" style="
                                width: 100%;
                                padding: 10px 12px;
                                border-radius: 8px;
                                border: 1.5px solid #e5e7eb;
                                background: white;
                                font-size: 15px;
                                color: #374151;
                                text-align: center;
                                outline: none;
                                font-weight: 600;
                            " onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e5e7eb'">
                        </td>
                        <td style="padding: 20px;">
                            <input name="items[total][]" readonly value="${parseFloat(p.price).toFixed(2)}" style="
                                width: 100%;
                                padding: 10px 12px;
                                border-radius: 8px;
                                border: 1.5px solid #e5e7eb;
                                background: #f1f5f9;
                                font-size: 15px;
                                color: #1e293b;
                                text-align: right;
                                font-weight: 700;
                            ">
                        </td>
                        <td style="padding: 20px;">
                            <button type="button" onclick="removeProduct('${rowId}')" style="
                                background: #fef2f2;
                                color: #dc2626;
                                border: 1.5px solid #fecaca;
                                padding: 8px 16px;
                                border-radius: 8px;
                                font-weight: 600;
                                font-size: 13px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 6px;
                                transition: all 0.2s;
                            " onmouseover="
                                this.style.background='#fee2e2';
                                this.style.borderColor='#fca5a5';
                            " onmouseout="
                                this.style.background='#fef2f2';
                                this.style.borderColor='#fecaca';
                            ">
                                <span>🗑️</span>
                                Remove
                            </button>
                        </td>
                    </tr>
                `);

                // Remove highlight after animation
                setTimeout(() => {
                    const newRow = document.getElementById(rowId);
                    if (newRow) newRow.style.background = '';
                }, 300);
            }

            calculate();
        }

        // Remove product from table
        function removeProduct(rowId) {
            const row = document.getElementById(rowId);
            if (row) {
                row.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    row.remove();
                    // Show empty state if no items
                    if (itemsTable.children.length === 1) { // Only empty state row left
                        emptyState.style.display = '';
                    }
                    calculate();
                }, 300);
            }
        }

        // Calculate totals
        function calculate() {
            let subTotal = 0;
            document.querySelectorAll('#itemsTable tr[data-pid]').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const price = parseFloat(row.querySelector('[name="items[price][]"]').value) || 0;
                const total = qty * price;
                row.querySelector('[name="items[total][]"]').value = total.toFixed(2);
                subTotal += total;
            });

            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const tax = parseFloat(document.getElementById('tax').value) || 0;
            const taxAmount = (subTotal * tax) / 100;
            const grandTotal = subTotal - discount + taxAmount;

            document.getElementById('sub_total').value = subTotal.toFixed(2);
            document.getElementById('grand_total').value = grandTotal.toFixed(2);
        }

        // Form submission handler
        function handleSubmit(form) {
            const btn = document.getElementById('saveBtn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = `
                <span style="
                    background: rgba(255, 255, 255, 0.2);
                    width: 36px;
                    height: 36px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    animation: spin 1s linear infinite;
                ">⏳</span>
                Processing...
            `;

            // Add spin animation
            const style = document.createElement('style');
            style.innerHTML = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);

            return true;
        }

        // Customer modal functions
        function openCustomerModal() {
            // Clear modal fields
            ['c_name', 'c_mobile', 'c_email', 'c_address'].forEach(id => {
                document.getElementById(id).value = '';
            });

            // Show modal with animation
            document.getElementById('customerModal').style.display = 'flex';
            document.getElementById('c_name').focus();
        }

        function closeCustomerModal() {
            document.getElementById('customerModal').style.display = 'none';
        }

        // Save customer via AJAX
        function saveCustomer() {
            if (isSavingCustomer) return;

            const name = document.getElementById('c_name').value.trim();
            const mobile = document.getElementById('c_mobile').value.trim();
            const email = document.getElementById('c_email').value.trim();
            const address = document.getElementById('c_address').value.trim();

            // Validation
            if (!name) {
                alert('Customer name is required');
                document.getElementById('c_name').focus();
                return;
            }
            if (!mobile) {
                alert('Mobile number is required');
                document.getElementById('c_mobile').focus();
                return;
            }

            isSavingCustomer = true;
            const originalText = saveCustomerBtn.innerHTML;
            saveCustomerBtn.innerHTML = `
                <span style="
                    display: inline-block;
                    width: 16px;
                    height: 16px;
                    border: 2px solid rgba(255,255,255,0.3);
                    border-top-color: white;
                    border-radius: 50%;
                    animation: buttonSpin 0.6s linear infinite;
                "></span>
                Saving...
            `;
            saveCustomerBtn.disabled = true;

            // Add spin animation for button
            const spinStyle = document.createElement('style');
            spinStyle.innerHTML = `
                @keyframes buttonSpin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(spinStyle);

            fetch("{{ route('customers.store.ajax') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        mobile: mobile,
                        email: email,
                        address: address
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.customer) {
                        // Add option if not exists
                        let existingOption = null;
                        for (let option of customerSelect.options) {
                            if (option.value == data.customer.id) {
                                existingOption = option;
                                break;
                            }
                        }

                        if (!existingOption) {
                            const option = document.createElement('option');
                            option.value = data.customer.id;
                            option.text = data.customer.name;
                            customerSelect.appendChild(option);
                        }

                        // Select the new customer
                        customerSelect.value = data.customer.id;
                        productSearch.disabled = false;

                        // Close modal and show success
                        closeCustomerModal();

                        // Show success toast
                        showToast('Customer added successfully!', 'success');

                        // Focus on product search
                        setTimeout(() => {
                            productSearch.focus();
                        }, 300);
                    } else {
                        alert(data.message || 'Error saving customer');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to save customer. Please try again.', 'error');
                })
                .finally(() => {
                    isSavingCustomer = false;
                    saveCustomerBtn.innerHTML = originalText;
                    saveCustomerBtn.disabled = false;
                    document.head.removeChild(spinStyle);
                });
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 30px;
                right: 30px;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                font-weight: 600;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideInRight 0.3s ease-out, fadeOut 0.3s ease-out 2.7s forwards;
                display: flex;
                align-items: center;
                gap: 12px;
            `;
            toast.innerHTML = `
                <span style="font-size: 20px;">${type === 'success' ? '✓' : '⚠'}</span>
                <span>${message}</span>
            `;

            // Add animations
            const toastStyle = document.createElement('style');
            toastStyle.innerHTML = `
                @keyframes slideInRight {
                    from {
                        opacity: 0;
                        transform: translateX(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
                @keyframes fadeOut {
                    from {
                        opacity: 1;
                    }
                    to {
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(toastStyle);

            document.body.appendChild(toast);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.remove();
                document.head.removeChild(toastStyle);
            }, 3000);
        }

        // Add animations
        const animationStyle = document.createElement('style');
        animationStyle.innerHTML = `
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes slideOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(10px);
                }
            }
        `;
        document.head.appendChild(animationStyle);
    </script>
@endsection
