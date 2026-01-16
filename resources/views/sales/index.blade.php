@extends('layouts.app')

@section('content')
    <style>
        /* Base styles */
        .sales-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            max-width: 1400px;
            margin: 30px auto;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            border: 1px solid #e5e7eb;
        }

        .sales-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
        }

        .sales-title {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .title-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
        }

        .title-text h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .title-text p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 15px;
        }

        /* New sale button */
        .new-sale-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
        }

        .new-sale-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(59, 130, 246, 0.4);
        }

        .new-sale-btn:active {
            transform: translateY(-1px);
        }

        /* Success message */
        .success-alert {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #065f46;
            padding: 18px 24px;
            border-radius: 14px;
            margin-bottom: 30px;
            border-left: 5px solid #10b981;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        .success-alert::before {
            content: "✓";
            background: #10b981;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
        }

        /* Table container */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            margin-bottom: 30px;
        }

        /* Table styles */
        .sales-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 800px;
        }

        .sales-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .sales-table th {
            padding: 20px 24px;
            text-align: left;
            font-weight: 700;
            color: #475569;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .sales-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .sales-table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.001);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .sales-table td {
            padding: 20px 24px;
            color: #475569;
            font-weight: 500;
        }

        /* Invoice number */
        .invoice-no {
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .invoice-no::before {
            content: "📄";
            font-size: 18px;
        }

        /* Date cell */
        .date-cell {
            color: #64748b;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-cell::before {
            content: "📅";
            font-size: 16px;
            opacity: 0.7;
        }

        /* Customer cell */
        .customer-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .customer-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .customer-name {
            font-weight: 600;
            color: #374151;
        }

        /* Total amount */
        .total-amount {
            font-weight: 800;
            color: #065f46;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .total-amount::before {
            content: "₹";
            font-weight: 700;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .print-btn {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 41, 59, 0.3);
        }

        .view-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .view-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
        }

        /* Empty state */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            background: #f8fafc;
        }

        .empty-content {
            max-width: 400px;
            margin: 0 auto;
            color: #94a3b8;
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 20px;
            display: block;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 10px;
        }

        .empty-description {
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        /* Pagination */
        .pagination-container {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .pagination a,
        .pagination span {
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            color: #475569;
            transition: all 0.2s;
            min-width: 40px;
            text-align: center;
        }

        .pagination a:hover {
            background: #f1f5f9;
            color: #3b82f6;
        }

        .pagination .active span {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .pagination .disabled span {
            color: #cbd5e1;
            cursor: not-allowed;
        }

        /* Status badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-paid {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        /* Stats cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin: 5px 0;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Filter bar */
        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-select {
            padding: 10px 16px;
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            background: white;
            font-size: 14px;
            color: #374151;
            min-width: 150px;
            outline: none;
            transition: all 0.2s;
        }

        .filter-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sales-container {
                padding: 20px;
            }

            .sales-header {
                flex-direction: column;
                gap: 20px;
                align-items: stretch;
            }

            .new-sale-btn {
                justify-content: center;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>

    <div class="sales-container">
        {{-- HEADER --}}
        <div class="sales-header">
            <div class="sales-title">
                <div class="title-icon">
                    <span style="font-size: 28px; color: white;">💰</span>
                </div>
                <div class="title-text">
                    <h1>Sales & Invoices</h1>
                    <p>Manage and track all your sales transactions</p>
                </div>
            </div>
            <a href="{{ route('sales.create') }}" class="new-sale-btn">
                <span style="font-size: 20px;">+</span>
                Create New Sale
            </a>
        </div>

        {{-- OPTIONAL: STATS CARDS --}}
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                    📊
                </div>
                <div class="stat-value">{{ $sales->total() }}</div>
                <div class="stat-label">Total Invoices</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                    ₹
                </div>
                <div class="stat-value">₹{{ number_format($sales->sum('grand_total'), 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white;">
                    👥
                </div>
                <div class="stat-value">{{ $customersCount ?? '0' }}</div>
                <div class="stat-label">Customers</div>
            </div>
        </div>

        {{-- OPTIONAL: FILTER BAR --}}
        {{--
        <div class="filter-bar">
            <select class="filter-select">
                <option>All Status</option>
                <option>Paid</option>
                <option>Pending</option>
            </select>
            <select class="filter-select">
                <option>This Month</option>
                <option>Last Month</option>
                <option>This Year</option>
            </select>
            <input type="text" placeholder="Search invoices..." class="filter-select" style="flex: 1;">
        </div>
        --}}

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="table-container">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>
                                <div class="invoice-no">
                                    {{ $sale->invoice_no }}
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="customer-cell">
                                    <div class="customer-avatar">
                                        {{ substr($sale->customer->name ?? 'W', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="customer-name">
                                            {{ $sale->customer->name ?? 'Walk-in Customer' }}
                                        </div>
                                        <div style="font-size: 12px; color: #94a3b8;">
                                            {{ $sale->customer->mobile ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-paid">
                                    Paid
                                </span>
                            </td>
                            <td>
                                <div class="total-amount">
                                    {{ number_format($sale->grand_total, 2) }}
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="view-btn">
                                        <span>👁️</span>
                                        View
                                    </a>
                                    <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank" class="print-btn">
                                        <span>🖨️</span>
                                        Print
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-content">
                                    <span class="empty-icon">📊</span>
                                    <div class="empty-title">No Sales Records Yet</div>
                                    <div class="empty-description">
                                        Start creating invoices to track your sales and revenue. Your first sale will appear
                                        here.
                                    </div>
                                    <a href="{{ route('sales.create') }}" class="new-sale-btn"
                                        style="display: inline-flex;">
                                        <span>+</span>
                                        Create Your First Sale
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($sales->hasPages())
            <div class="pagination-container">
                <div class="pagination">
                    {{ $sales->links() }}
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Pagination icon fix */
        .w-5.h-5 {
            width: 20px;
            height: 20px;
        }

        /* Custom pagination styling */
        svg {
            width: 20px;
            height: 20px;
        }
    </style>
@endsection
