<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /* ================= LIST ================= */
    public function index()
    {
        $customers = Customer::latest()->paginate(3);
        return view('customers.index', compact('customers'));
    }

    /* ================= AJAX LIVE SEARCH ================= */
public function ajaxSearch(Request $request)
{
    $search = $request->search;

    $customers = Customer::where('name', 'like', "%{$search}%")
        ->orWhere('mobile', 'like', "%{$search}%")
        ->orWhere('email', 'like', "%{$search}%")
        ->latest()
        ->limit(20)
        ->get(['id', 'name', 'mobile', 'email', 'gst_no']); // Specify columns

    return response()->json($customers);
}
    /* ================= CREATE ================= */
    public function create()
    {
        return view('customers.create');
    }

public function store(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:255',
        'mobile' => 'required|string|max:15|unique:customers,mobile',
        'email'  => 'nullable|email|unique:customers,email',
    ]);

    Customer::create([
        'name'    => trim($request->name),
        'mobile'  => trim($request->mobile),
        'email'   => trim($request->email),
        'gst_no'  => trim($request->gst_no),
        'address' => trim($request->address),
    ]);

    return redirect()
        ->route('customers.index')
        ->with('success', 'Customer added successfully');
}

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

  public function update(Request $request, $id)
{
    $customer = Customer::findOrFail($id);

    $request->validate([
        'name'   => 'required|string|max:255',
        'mobile' => 'required|string|max:15|unique:customers,mobile,' . $customer->id,
        'email'  => 'nullable|email|unique:customers,email,' . $customer->id,
    ]);

    $customer->update([
        'name'    => trim($request->name),
        'mobile'  => trim($request->mobile),
        'email'   => trim($request->email),
        'gst_no'  => trim($request->gst_no),
        'address' => trim($request->address),
    ]);

    return redirect()
        ->route('customers.index')
        ->with('success', 'Customer updated');
}


    /* ================= DELETE ================= */
    public function destroy($id)
    {
        Customer::destroy($id);
        return back()->with('success', 'Customer deleted');
    }

    /* ================= AJAX STORE (FROM SALES PAGE) ================= */
 public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'name'    => 'required|string|max:255',
                'mobile'  => 'required|string|max:20',
                'email'   => 'nullable|email',
                'address' => 'nullable|string',
            ]);

            $customer = Customer::create([
                'name'    => $request->name,
                'mobile'  => $request->mobile,
                'email'   => $request->email,
                'address' => $request->address,
            ]);

            return response()->json([
                'success'  => true,
                'customer' => $customer
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /* ================= CUSTOMER SALES (👁 VIEW BUTTON) ================= */
    public function sales(Customer $customer)
    {
        $sales = $customer->sales()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('customers.sales', compact('customer', 'sales'));
    }
}
