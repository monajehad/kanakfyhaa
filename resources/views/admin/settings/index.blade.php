@extends('layouts/layoutMaster')

@section('title', __('Settings'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">⚙️ {{ __('Settings') }}</h4>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        @foreach($groups as $groupKey => $groupLabel)
            <li class="nav-item">
                <button class="nav-link {{ ((session('activeTab') ?? 'general') === $groupKey || (empty(session('activeTab')) && $loop->first)) ? 'active' : '' }}" id="{{ $groupKey }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $groupKey }}" type="button" role="tab">
                    {{ $groupLabel }}
                </button>
            </li>
        @endforeach
    </ul>

    <!-- Tab Content -->
    <form method="POST" action="{{ route('admin.settings.store') }}" class="needs-validation">
        @csrf
        <input type="hidden" name="active_tab" id="activeTabInput" value="{{ session('activeTab') ?? 'general' }}">

        <div class="tab-content">
            @foreach($groups as $groupKey => $groupLabel)
                <div class="tab-pane fade {{ ((session('activeTab') ?? 'general') === $groupKey || (empty(session('activeTab')) && $loop->first)) ? 'show active' : '' }}" id="{{ $groupKey }}" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            @if(isset($settings[$groupKey]) && count($settings[$groupKey]) > 0)
                                @foreach($settings[$groupKey] as $setting)
                                    @include('admin.settings.fields.' . $setting->type, ['setting' => $setting])
                                @endforeach
                            @else
                                <p class="text-muted">{{ __('No settings available for this group') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-save me-2"></i>{{ __('Save Changes') }}
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Track which tab is active and save it to hidden input before form submission
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            const activeTabId = e.target.id.replace('-tab', '');
            document.getElementById('activeTabInput').value = activeTabId;
        });
    });
    
    // Set initial active tab on page load
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('active_tab') || document.querySelector('[data-bs-toggle="tab"].active')?.id.replace('-tab', '');
    if (activeTab) {
        document.getElementById('activeTabInput').value = activeTab;
    }
</script>
@endpush
@endsection
