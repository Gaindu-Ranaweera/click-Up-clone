<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title-dash">Client Coordination & Follow-ups</h4>
                <button type="button" class="btn btn-primary btn-lg text-white" data-bs-toggle="modal" data-bs-target="#addClientModal">
                    <i class="mdi mdi-plus"></i> Add Company
                </button>
            </div>

            <div class="row">
                @forelse($clients as $client)
                <div class="col-md-6 mb-4">
                    <div class="card card-rounded shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1 text-primary">{{ $client->company_name }}</h5>
                                    <p class="text-muted small mb-0">
                                        <i class="mdi mdi-account me-1"></i> {{ $client->contact_person ?? 'No contact' }} | 
                                        <i class="mdi mdi-phone me-1"></i> {{ $client->phone ?? 'No phone' }}
                                    </p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addFollowupModal-{{ $client->id }}">
                                            <i class="mdi mdi-comment-plus-outline me-2 text-success"></i>Add Follow-up
                                        </button>
                                        @if(auth()->user()->hasPermission('module_client_coordination', 'can_delete'))
                                        <form action="{{ route('client-coordination.destroy', $client) }}" method="POST" onsubmit="return confirm('Delete this client?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="mdi mdi-trash-can-outline me-2"></i>Delete
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="followup-timeline" style="max-height: 200px; overflow-y: auto;">
                                <h6 class="text-muted small fw-bold mb-2">FOLLOW-UP HISTORY</h6>
                                @forelse($client->followups()->latest()->get() as $followup)
                                <div class="border-left ps-3 pb-3 position-relative">
                                    <div class="position-absolute bg-primary rounded-circle" style="width: 8px; height: 8px; left: -4px; top: 5px;"></div>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-1 small fw-bold">{{ $followup->response_type ?? 'Contact' }}: {{ $followup->remarks }}</p>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $followup->created_at->format('M d, H:i') }}</small>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem;">By {{ $followup->createdBy->name }}</small>
                                </div>
                                @empty
                                <p class="text-muted small italic">No follow-ups recorded yet.</p>
                                @endforelse
                            </div>

                            <div class="mt-3 pt-2 border-top">
                                <small class="text-muted">Created by: {{ $client->createdBy->name }} at {{ $client->created_at->format('Y-m-d H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Follow-up Modal -->
                <div class="modal fade" id="addFollowupModal-{{ $client->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Follow-up for {{ $client->company_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('client-coordination.followup', $client) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Remarks / Conversation Details</label>
                                        <textarea name="remarks" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">Type</label>
                                            <select name="response_type" class="form-select">
                                                <option value="Call">Call</option>
                                                <option value="Email">Email</option>
                                                <option value="Meeting">Meeting</option>
                                                <option value="WhatsApp">WhatsApp</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Next Follow-up (Optional)</label>
                                            <input type="datetime-local" name="followup_date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary text-white">Save Follow-up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">No clients found. Click "Add Company" to start.</div>
                </div>
                @endforelse
            </div>
            
            <div class="mt-4">
                {{ $clients->links() }}
            </div>
        </div>
    </div>

    <!-- Add Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('client-coordination.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" name="company_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-white">Save Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
