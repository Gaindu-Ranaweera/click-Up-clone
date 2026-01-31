<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientFollowup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientCoordinationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('super_admin') || $user->hasRole('admin');
        $showArchived = $request->get('archived') === '1';

        // Base query
        $query = Client::with(['createdBy', 'followups']);

        // Filter by archived status
        if ($showArchived) {
            $query->archived();
        } else {
            $query->active();
        }

        if ($isAdmin) {
            // Admin can see all clients with optional filtering
            
            // Filter by user
            if ($request->filled('user_id')) {
                $query->where('created_by', $request->user_id);
            }

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                      ->orWhere('contact_person', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Filter clients with no follow-ups
            if ($request->filled('no_followups') && $request->no_followups == '1') {
                $query->doesntHave('followups');
            }

            // Get users who have created clients for the filter dropdown
            $usersWithClients = User::whereHas('clients')->get();
        } else {
            // Regular users can only see their own clients
            $query->where('created_by', $user->id);
            $usersWithClients = collect(); // Empty collection for non-admins
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

        // Count for tabs
        $activeCount = Client::active()->when(!$isAdmin, fn($q) => $q->where('created_by', $user->id))->count();
        $archivedCount = Client::archived()->when(!$isAdmin, fn($q) => $q->where('created_by', $user->id))->count();

        return view('modules.client-coordination.index', compact(
            'clients', 'isAdmin', 'usersWithClients', 'showArchived', 'activeCount', 'archivedCount'
        ));
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

    /**
     * Archive a client.
     */
    public function archive(Client $client)
    {
        $client->update(['is_archived' => true]);
        return back()->with('success', 'Client archived successfully.');
    }

    /**
     * Restore an archived client.
     */
    public function restore(Client $client)
    {
        $client->update(['is_archived' => false]);
        return back()->with('success', 'Client restored successfully.');
    }

    /**
     * Update client status color.
     */
    public function updateColor(Request $request, Client $client)
    {
        $request->validate([
            'status_color' => 'nullable|string|max:20',
        ]);

        $client->update(['status_color' => $request->status_color]);
        return back()->with('success', 'Client color updated.');
    }
}
