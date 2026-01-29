<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\FeatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'feature_id' => 'required|exists:features,id',
            'reason' => 'required|string|max:500',
        ]);

        // Check if a pending request already exists
        $exists = FeatureRequest::where('user_id', Auth::id())
            ->where('feature_id', $request->feature_id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already have a pending request for this module.');
        }

        FeatureRequest::create([
            'user_id' => Auth::id(),
            'feature_id' => $request->feature_id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Access request sent to administrator.');
    }

    public function index()
    {
        // Admin view for request management
        $requests = FeatureRequest::with(['user', 'feature'])->latest()->paginate(10);
        return view('admin.feature-requests.index', compact('requests'));
    }

    public function handle(Request $request, FeatureRequest $featureRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
        ]);

        $featureRequest->update([
            'status' => $request->status,
        ]);

        if ($request->status === 'approved') {
            $featureRequest->user->features()->syncWithoutDetaching([
                $featureRequest->feature_id => [
                    'is_enabled' => true,
                    'can_edit' => $request->boolean('can_edit', true),
                    'can_delete' => $request->boolean('can_delete', true),
                ]
            ]);
        }

        return back()->with('success', 'Request ' . $request->status . ' successfully.');
    }
}
