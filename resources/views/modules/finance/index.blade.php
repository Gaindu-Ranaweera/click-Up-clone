<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card card-rounded">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title card-title-dash">Invoices</h4>
                            <p class="card-subtitle card-subtitle-dash">Manage and track client invoices</p>
                        </div>
                        <div>
                            <a href="{{ route('finance.invoices.create') }}" class="btn btn-primary btn-lg text-white mb-0 me-0" type="button">
                                <i class="mdi mdi-plus"></i> Create New Invoice
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table select-table">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td>
                                            <h6>{{ $invoice->invoice_number }}</h6>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <div>
                                                    <h6>{{ $invoice->client->company_name }}</h6>
                                                    <p>{{ $invoice->client->contact_person }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p>{{ date('M d, Y', strtotime($invoice->invoice_date)) }}</p>
                                        </td>
                                        <td>
                                            <h6>${{ number_format($invoice->total_amount, 2) }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-success">${{ number_format($invoice->paid_amount, 2) }}</p>
                                        </td>
                                        <td>
                                            <p class="text-danger">${{ number_format($invoice->balance_amount, 2) }}</p>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'unpaid' => 'badge-opacity-danger',
                                                    'partially_paid' => 'badge-opacity-warning',
                                                    'paid' => 'badge-opacity-success',
                                                    'cancelled' => 'badge-opacity-secondary'
                                                ][$invoice->status] ?? 'badge-opacity-info';
                                            @endphp
                                            <div class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('finance.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary me-2">View</a>
                                                @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasPermission('module_finance', 'edit'))
                                                    <a href="{{ route('finance.invoices.edit', $invoice) }}" class="btn btn-sm btn-outline-warning me-2">Edit</a>
                                                @endif

                                                @if(auth()->user()->hasRole('super_admin'))
                                                    <form action="{{ route('finance.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No invoices found.</td>
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
