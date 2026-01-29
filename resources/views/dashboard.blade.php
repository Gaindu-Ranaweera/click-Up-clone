<x-app-layout>
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="statistics-title">Active Features</p>
                                        <h3 class="rate-percentage">{{ auth()->user()->features->where('pivot.is_enabled', true)->count() }}</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>Enabled</span></p>
                                    </div>
                                    @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                                    <div>
                                        <p class="statistics-title">Total Users</p>
                                        <h3 class="rate-percentage">{{ \App\Models\User::count() }}</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-account-multiple"></i><span>Registered</span></p>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Active Users</p>
                                        <h3 class="rate-percentage">{{ \App\Models\User::where('is_active', true)->count() }}</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-check-circle"></i><span>Online</span></p>
                                    </div>
                                    @endif
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Your Role</p>
                                        <h3 class="rate-percentage text-capitalize">{{ str_replace('_', ' ', auth()->user()->role->name) }}</h3>
                                        <p class="text-muted d-flex"><i class="mdi mdi-shield-account"></i><span>Access Level</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-8 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Welcome to {{ config('app.name') }}</h4>
                                                        <p class="card-subtitle card-subtitle-dash">Your enterprise management dashboard</p>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h5>Your Enabled Modules:</h5>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#requestFeatureModal">
                                                            <i class="mdi mdi-plus-circle me-1"></i> Request Access
                                                        </button>
                                                    </div>
                                                    <div class="row mt-3">
                                                        @forelse(auth()->user()->features->where('pivot.is_enabled', true) as $feature)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="card border-primary">
                                                                <div class="card-body">
                                                                    <h6 class="card-title">
                                                                        <i class="mdi mdi-check-circle text-success me-2"></i>
                                                                        {{ $feature->name }}
                                                                    </h6>
                                                                    <p class="card-text text-muted small mb-0">{{ $feature->description }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @empty
                                                        <div class="col-12">
                                                            <div class="alert alert-warning" role="alert">
                                                                <i class="mdi mdi-alert me-2"></i>
                                                                No features enabled. Contact your administrator.
                                                            </div>
                                                        </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h4 class="card-title card-title-dash">Quick Stats</h4>
                                                        </div>
                                                        <div class="mt-3">
                                                            <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                                <div class="d-flex">
                                                                    <div class="wrapper ms-3">
                                                                        <p class="ms-1 mb-1 fw-bold">Account Status</p>
                                                                        <small class="text-muted mb-0">
                                                                            <span class="badge badge-opacity-{{ auth()->user()->is_active ? 'success' : 'danger' }}">
                                                                                {{ auth()->user()->is_active ? 'Active' : 'Inactive' }}
                                                                            </span>
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                                <div class="d-flex">
                                                                    <div class="wrapper ms-3">
                                                                        <p class="ms-1 mb-1 fw-bold">Member Since</p>
                                                                        <small class="text-muted mb-0">{{ auth()->user()->created_at->format('M d, Y') }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="wrapper d-flex align-items-center justify-content-between pt-2">
                                                                <div class="d-flex">
                                                                    <div class="wrapper ms-3">
                                                                        <p class="ms-1 mb-1 fw-bold">Last Login</p>
                                                                        <small class="text-muted mb-0">{{ now()->format('M d, Y H:i A') }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Request Feature Modal -->
<div class="modal fade" id="requestFeatureModal" tabindex="-1" aria-labelledby="requestFeatureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestFeatureModalLabel">Request Module Access</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('feature-requests.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="feature_id" class="form-label">Select Module</label>
                        <select name="feature_id" id="feature_id" class="form-select" required>
                            <option value="">Choose a module...</option>
                            @php
                                $userFeatureIds = auth()->user()->features->pluck('id')->toArray();
                                $availableFeatures = \App\Models\Feature::whereNotIn('id', $userFeatureIds)->get();
                            @endphp
                            @foreach($availableFeatures as $f)
                                <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Why do you need this?</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" required placeholder="Describe your business need..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('plugin-scripts')
<script src="{{ asset('backend/assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('backend/assets/js/jquery.cookie.js') }}"></script>
<script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>
@endpush
