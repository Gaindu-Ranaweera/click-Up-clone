<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientCoordinationController extends Controller
{
    public function index()
    {
        $clients = Client::with(['createdBy', 'followups'])->latest()->paginate(10);
        return view('modules.client-coordination.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Client::create([
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Company added successfully.');
    }

    public function storeFollowup(Request $request, Client $client)
    {
        $request->validate([
            'remarks' => 'required|string',
            'response_type' => 'nullable|string',
            'followup_date' => 'nullable|date',
        ]);

        $client->followups()->create([
            'remarks' => $request->remarks,
            'response_type' => $request->response_type,
            'followup_date' => $request->followup_date,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Follow-up added successfully.');
    }

    public function update(Request $request, Client $client)
    {
        if (!Auth::user()->hasPermission('module_client_coordination', 'can_edit')) {
            return back()->with('error', 'You do not have permission to edit entries.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $client->update($request->only(['company_name', 'contact_person', 'phone', 'email', 'address']));

        return back()->with('success', 'Company details updated.');
    }

    public function destroy(Client $client)
    {
        if (!Auth::user()->hasPermission('module_client_coordination', 'can_delete')) {
            return back()->with('error', 'You do not have permission to delete entries.');
        }

        $client->delete();
        return back()->with('success', 'Client deleted successfully.');
    }
}
