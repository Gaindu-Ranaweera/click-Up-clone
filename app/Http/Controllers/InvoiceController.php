<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->get();
        return view('modules.finance.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::latest()->get();
        return view('modules.finance.create', compact('clients'));
    }

    public function getClientDetails(Client $client)
    {
        return response()->json($client);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $total_amount = collect($request->items)->sum('amount');
            $advance = $request->advance_amount ?? 0;
            $balance = $total_amount - $advance;
            $status = $advance >= $total_amount ? 'paid' : ($advance > 0 ? 'partially_paid' : 'unpaid');

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'client_id' => $request->client_id,
                'invoice_date' => $request->invoice_date,
                'total_amount' => $total_amount,
                'advance_amount' => $advance,
                'paid_amount' => $advance,
                'balance_amount' => $balance,
                'status' => $status,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                ]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Created Invoice',
                'model_type' => Invoice::class,
                'model_id' => $invoice->id,
                'details' => json_encode([
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $total_amount,
                    'client' => $invoice->client->company_name
                ]),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('finance.index')->with('success', 'Invoice created successfully: ' . $invoice->invoice_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating invoice: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'items', 'creator']);
        return view('modules.finance.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        // Only superadmin or authorized users can edit
        if (!auth()->user()->hasRole('super_admin') && !auth()->user()->hasPermission('module_finance', 'edit')) {
            return back()->with('error', 'You do not have permission to edit this invoice.');
        }

        $invoice->load('items');
        $clients = Client::latest()->get();
        return view('modules.finance.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // Only superadmin or authorized users
        if (!auth()->user()->hasRole('super_admin') && !auth()->user()->hasPermission('module_finance', 'edit')) {
            return back()->with('error', 'You do not have permission to edit this invoice.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $total_amount = collect($request->items)->sum('amount');
            $advance = $request->advance_amount ?? 0;
            $balance = $total_amount - $advance;
            $status = $advance >= $total_amount ? 'paid' : ($advance > 0 ? 'partially_paid' : 'unpaid');

            $invoice->update([
                'client_id' => $request->client_id,
                'invoice_date' => $request->invoice_date,
                'total_amount' => $total_amount,
                'advance_amount' => $advance,
                'paid_amount' => $advance,
                'balance_amount' => $balance,
                'status' => $status,
                'notes' => $request->notes,
            ]);

            // Refresh items
            $invoice->items()->delete();
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                ]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Updated Invoice',
                'model_type' => Invoice::class,
                'model_id' => $invoice->id,
                'details' => json_encode([
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $total_amount,
                    'changes' => 'Invoice details updated'
                ]),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('finance.index')->with('success', 'Invoice updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating invoice: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Invoice $invoice)
    {
        // Strictly only super_admin can delete
        if (!auth()->user()->hasRole('super_admin')) {
            return back()->with('error', 'Only Super Admin can delete invoices.');
        }

        try {
            $invoiceNumber = $invoice->invoice_number;
            $invoice->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted Invoice',
                'model_type' => Invoice::class,
                'model_id' => null,
                'details' => json_encode([
                    'invoice_number' => $invoiceNumber,
                    'action' => 'Invoice deleted'
                ]),
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('finance.index')->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }

    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $prefix = 'INV-' . $year . '-';
        
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->latest()
            ->first();

        if ($lastInvoice) {
            $lastNumber = str_replace($prefix, '', $lastInvoice->invoice_number);
            $newNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }
}
