<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title-dash">Client Coordination & Follow-ups</h4>
                <button type="button" class="btn btn-primary btn-lg text-white" data-bs-toggle="modal" data-bs-target="#addClientModal">
                    <i class="mdi mdi-plus"></i> Add Company
                </button>
            </div>

            <!-- Tabs for Active/Archived -->
            <ul class="nav nav-tabs cus_gk_tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ !$showArchived ? 'active' : '' }}" href="{{ route('client-coordination.index') }}">
                        <i class="mdi mdi-account-check me-1"></i> Active Clients
                        <span class="badge bg-success ms-2">{{ $activeCount }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $showArchived ? 'active' : '' }}" href="{{ route('client-coordination.index', ['archived' => '1']) }}">
                        <i class="mdi mdi-archive me-1"></i> Archived
                        <span class="badge bg-secondary ms-2">{{ $archivedCount }}</span>
                    </a>
                </li>
            </ul>

            @if($isAdmin)
            <!-- Admin Filter Section -->
            <div class="card card-rounded mb-4">
                <div class="card-body py-3">
                    <form action="{{ route('client-coordination.index') }}" method="GET" class="row g-3 align-items-end">
                        @if($showArchived)
                        <input type="hidden" name="archived" value="1">
                        @endif
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Filter by User</label>
                            <select name="user_id" class="form-select cus_gk_filter_select">
                                <option value="">All Users</option>
                                @foreach($usersWithClients as $filterUser)
                                    <option value="{{ $filterUser->id }}" {{ request('user_id') == $filterUser->id ? 'selected' : '' }}>
                                        {{ $filterUser->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">Search Clients</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span>
                                <input type="text" name="search" class="form-control cus_gk_search_input" 
                                       placeholder="Company name, contact, email..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="no_followups" value="1" 
                                       id="noFollowups" {{ request('no_followups') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="noFollowups">No follow-ups only</label>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary text-white flex-grow-1">
                                <i class="mdi mdi-filter"></i> Filter
                            </button>
                            <a href="{{ route('client-coordination.index', $showArchived ? ['archived' => '1'] : []) }}" class="btn btn-outline-secondary" title="Reset">
                                <i class="mdi mdi-refresh"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <div class="row">
                @forelse($clients as $client)
                <div class="col-md-6 mb-4">
                    <div class="card card-rounded shadow-sm {{ $client->status_color ? 'cus_gk_card_' . $client->status_color : '' }}" 
                         style="{{ $client->status_color ? 'border-left: 4px solid var(--cus-gk-color-' . $client->status_color . ')' : '' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h5 class="fw-bold mb-0 text-primary">{{ $client->company_name }}</h5>
                                        @if($client->status_color)
                                        <span class="cus_gk_color_dot cus_gk_color_{{ $client->status_color }}" title="{{ ucfirst($client->status_color) }}"></span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-0">
                                        <i class="mdi mdi-account me-1"></i> {{ $client->contact_person ?? 'No contact' }} | 
                                        <i class="mdi mdi-phone me-1"></i> {{ $client->phone ?? 'No phone' }}
                                    </p>
                                    @if($isAdmin)
                                    <span class="badge badge-opacity-primary mt-1">
                                        <i class="mdi mdi-account-circle me-1"></i>{{ $client->createdBy->name }}
                                    </span>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        @if(!$showArchived)
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addFollowupModal-{{ $client->id }}">
                                            <i class="mdi mdi-comment-plus-outline me-2 text-success"></i>Add Follow-up
                                        </button>
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#colorModal-{{ $client->id }}">
                                            <i class="mdi mdi-palette me-2 text-info"></i>Set Color
                                        </button>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('client-coordination.archive', $client) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-warning">
                                                <i class="mdi mdi-archive me-2"></i>Archive
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('client-coordination.restore', $client) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="mdi mdi-restore me-2"></i>Restore
                                            </button>
                                        </form>
                                        @endif
                                        @if(auth()->user()->hasPermission('module_client_coordination', 'delete'))
                                        <form action="{{ route('client-coordination.destroy', $client) }}" method="POST" onsubmit="return confirm('Delete this client permanently?')">
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

                <!-- Color Picker Modal -->
                <div class="modal fade" id="colorModal-{{ $client->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Set Status Color</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('client-coordination.updateColor', $client) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body">
                                    <p class="text-muted small mb-3">Select a color to indicate the client's status:</p>
                                    <div class="cus_gk_color_palette">
                                        <label class="cus_gk_color_option">
                                            <input type="radio" name="status_color" value="" {{ !$client->status_color ? 'checked' : '' }}>
                                            <span class="cus_gk_color_swatch cus_gk_color_none" title="No Color"></span>
                                        </label>
                                        <label class="cus_gk_color_option">
                                            <input type="radio" name="status_color" value="green" {{ $client->status_color === 'green' ? 'checked' : '' }}>
                                            <span class="cus_gk_color_swatch cus_gk_color_green" title="Hot Lead"></span>
                                        </label>
                                        <label class="cus_gk_color_option">
                                            <input type="radio" name="status_color" value="yellow" {{ $client->status_color === 'yellow' ? 'checked' : '' }}>
                                            <span class="cus_gk_color_swatch cus_gk_color_yellow" title="Warm Lead"></span>
                                        </label>
                                        <label class="cus_gk_color_option">
                                            <input type="radio" name="status_color" value="orange" {{ $client->status_color === 'orange' ? 'checked' : '' }}>
                                            <span class="cus_gk_color_swatch cus_gk_color_orange" title="Follow Up"></span>
                                        </label>
                                        <label class="cus_gk_color_option">
                                            <input type="radio" name="status_color" value="red" {{ $client->status_color === 'red' ? 'checked' : '' }}>
                                            <span class="cus_gk_color_swatch cus_gk_color_red" title="Cold"></span>
                                        </label>
                                        <label class="cus_gk_color_option">
                                            <input type="radio" name="status_color" value="blue" {{ $client->status_color === 'blue' ? 'checked' : '' }}>
                                            <span class="cus_gk_color_swatch cus_gk_color_blue" title="In Progress"></span>
                                        </label>
                                        <label class="cus_gk_color_option">
                                            <input type="radio" name="status_color" value="purple" {{ $client->status_color === 'purple' ? 'checked' : '' }}>
                                            <span class="cus_gk_color_swatch cus_gk_color_purple" title="VIP"></span>
                                        </label>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <span class="cus_gk_color_dot cus_gk_color_green"></span> Hot Lead &nbsp;
                                            <span class="cus_gk_color_dot cus_gk_color_yellow"></span> Warm &nbsp;
                                            <span class="cus_gk_color_dot cus_gk_color_orange"></span> Follow Up<br>
                                            <span class="cus_gk_color_dot cus_gk_color_red"></span> Cold &nbsp;
                                            <span class="cus_gk_color_dot cus_gk_color_blue"></span> In Progress &nbsp;
                                            <span class="cus_gk_color_dot cus_gk_color_purple"></span> VIP
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary text-white">Save Color</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        @if($showArchived)
                            No archived clients found.
                        @elseif($isAdmin && (request('search') || request('user_id') || request('no_followups')))
                            No clients found matching your filter criteria. <a href="{{ route('client-coordination.index') }}">Reset filters</a>
                        @else
                            No clients found. Click "Add Company" to start.
                        @endif
                    </div>
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
