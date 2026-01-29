<x-app-layout>
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">System Activity Logs</h2>

            <div class="card card-rounded">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Entity</th>
                                    <th>Details</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('M d, H:i:s') }}</td>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                    <td>
                                        <span class="badge badge-opacity-{{ $log->action === 'created' ? 'success' : ($log->action === 'deleted' ? 'danger' : 'info') }}">
                                            {{ strtoupper($log->action) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ class_basename($log->model_type) }} #{{ $log->model_id }}</small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-link btn-sm" data-bs-toggle="collapse" data-bs-target="#details-{{ $log->id }}">
                                            View Details
                                        </button>
                                        <div class="collapse mt-2" id="details-{{ $log->id }}">
                                            <pre class="bg-light p-2 rounded" style="font-size: 0.7rem;">{{ json_encode($log->details, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </td>
                                    <td><small>{{ $log->ip_address }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No logs recorded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
