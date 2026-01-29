<x-app-layout>
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Module Access Requests</h2>

            <div class="card card-rounded">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Feature</th>
                                    <th>Reason</th>
                                    <th>Requested At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $req)
                                <tr>
                                    <td>{{ $req->user->name }}</td>
                                    <td>{{ $req->feature->name }}</td>
                                    <td><small>{{ $req->reason }}</small></td>
                                    <td>{{ $req->created_at->diffForHumans() }}</td>
                                    <td>
                                        <span class="badge badge-opacity-{{ $req->status === 'pending' ? 'warning' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                                            {{ ucfirst($req->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($req->status === 'pending')
                                        <form action="{{ route('admin.feature-requests.handle', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="can_edit" value="1" checked>
                                                <label class="form-check-label">Edit</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="can_delete" value="1" checked>
                                                <label class="form-check-label">Delete</label>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-success text-white">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.feature-requests.handle', $req) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger text-white">Reject</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No pending requests.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
