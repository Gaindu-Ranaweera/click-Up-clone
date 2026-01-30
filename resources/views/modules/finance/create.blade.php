<x-app-layout>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card card-rounded">
                <div class="card-body">
                    <h4 class="card-title">Create New Invoice</h4>
                    <p class="card-description">Enter invoice details and add solutions/addons.</p>
                    
                    <form action="{{ route('finance.invoices.store') }}" method="POST" id="invoiceForm">
                        @csrf
                        
                        <div class="row mt-4">
                            <div class="col-md-6 border-right">
                                <h5 class="mb-3 text-primary">Client Information</h5>
                                <div class="form-group">
                                    <label>Select Client</label>
                                    <select name="client_id" id="client_id" class="form-control" required>
                                        <option value="">-- Choose Client --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="clientDetails" class="mt-3 p-3 bg-light rounded" style="display:none;">
                                    <p><strong>Contact Person:</strong> <span id="contact_person"></span></p>
                                    <p><strong>Email:</strong> <span id="client_email"></span></p>
                                    <p><strong>Phone:</strong> <span id="client_phone"></span></p>
                                    <p><strong>Address:</strong> <span id="client_address"></span></p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="mb-3 text-primary">Invoice Details</h5>
                                <div class="form-group">
                                    <label>Invoice Date</label>
                                    <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Generate ID</label>
                                    <input type="text" class="form-control" value="Auto-generated" disabled>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3 text-primary">Service/Product Solutions & Addons</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Description</th>
                                        <th width="200">Amount ($)</th>
                                        <th width="80"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <input type="text" name="items[0][description]" class="form-control" placeholder="Solution name or addon" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][amount]" class="form-control item-amount" placeholder="0.00" step="0.01" min="0" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row" disabled><i class="mdi mdi-delete"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-2 text-right">
                                <button type="button" class="btn btn-outline-info btn-sm" id="addRow">
                                    <i class="mdi mdi-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4 justify-content-end">
                            <div class="col-md-4">
                                <ul class="list-group list-group-flush border-top">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>Total Amount:</strong>
                                        <span id="displayTotal">$0.00</span>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-group mb-0">
                                            <label>Advance Payment ($)</label>
                                            <input type="number" name="advance_amount" id="advance_amount" class="form-control form-control-sm" placeholder="0.00" step="0.01" min="0">
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between bg-light">
                                        <strong>Balance Due:</strong>
                                        <span id="displayBalance" class="text-danger font-weight-bold">$0.00</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label>Notes / Terms</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Additional details or terms..."></textarea>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg text-white">
                                <i class="mdi mdi-content-save"></i> Save Invoice
                            </button>
                            <a href="{{ route('finance.index') }}" class="btn btn-light btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            let rowCount = 1;

            // Fetch Client Details
            $('#client_id').change(function() {
                let clientId = $(this).val();
                if (clientId) {
                    $.get('{{ url("finance/clients") }}/' + clientId, function(data) {
                        $('#contact_person').text(data.contact_person || 'N/A');
                        $('#client_email').text(data.email || 'N/A');
                        $('#client_phone').text(data.phone || 'N/A');
                        $('#client_address').text(data.address || 'N/A');
                        $('#clientDetails').fadeIn();
                    });
                } else {
                    $('#clientDetails').fadeOut();
                }
            });

            // Add Row
            $('#addRow').click(function() {
                let newRow = `
                    <tr class="item-row">
                        <td>
                            <input type="text" name="items[${rowCount}][description]" class="form-control" placeholder="Solution name or addon" required>
                        </td>
                        <td>
                            <input type="number" name="items[${rowCount}][amount]" class="form-control item-amount" placeholder="0.00" step="0.01" min="0" required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-row"><i class="mdi mdi-delete"></i></button>
                        </td>
                    </tr>`;
                $('#itemsTable tbody').append(newRow);
                rowCount++;
                updateCalculations();
            });

            // Remove Row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateCalculations();
            });

            // Update Calculations
            $(document).on('input', '.item-amount, #advance_amount', function() {
                updateCalculations();
            });

            function updateCalculations() {
                let total = 0;
                $('.item-amount').each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    total += val;
                });

                let advance = parseFloat($('#advance_amount').val()) || 0;
                let balance = total - advance;

                $('#displayTotal').text('$' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                $('#displayBalance').text('$' + balance.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));

                if (balance <= 0) {
                    $('#displayBalance').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#displayBalance').removeClass('text-success').addClass('text-danger');
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
