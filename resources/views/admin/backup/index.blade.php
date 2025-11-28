@extends('layouts/layoutMaster')

@section('title', __('System Backup'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('System Backup') }}</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Backup Actions -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">{{ __('Export Backup') }}</h5>
                                    <p class="card-text text-muted small">{{ __('Download a complete backup of your system including database, settings, and uploaded files.') }}</p>
                                    <form action="{{ route('admin.backup.export') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-download-cloud-line me-2"></i>{{ __('Export Backup') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">{{ __('Import Backup') }}</h5>
                                    <p class="card-text text-muted small">{{ __('Restore your system from a previously exported backup file.') }}</p>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="ri-upload-cloud-line me-2"></i>{{ __('Import Backup') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backup Files Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Backup History') }}</h5>
                        </div>
                        @if (count($backups) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('Backup File') }}</th>
                                            <th>{{ __('Size') }}</th>
                                            <th>{{ __('Created') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($backups as $backup)
                                            <tr>
                                                <td>
                                                    <i class="ri-file-zip-line me-2"></i>
                                                    <strong>{{ $backup['name'] }}</strong>
                                                </td>
                                                <td>{{ $backup['size'] }}</td>
                                                <td>{{ $backup['created_at'] }}</td>
                                                <td>
                                                    <a href="{{ route('admin.backup.download', $backup['name']) }}" class="btn btn-sm btn-info" title="{{ __('Download') }}">
                                                        <i class="ri-download-line"></i>
                                                    </a>
                                                    <form action="{{ route('admin.backup.delete', $backup['name']) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card-body">
                                <p class="text-muted text-center py-4">{{ __('No backups available yet') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">{{ __('Import Backup') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.backup.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <strong>{{ __('Warning!') }}</strong> {{ __('Importing a backup will overwrite your current system data.') }}
                    </div>
                    <div class="mb-3">
                        <label for="backup_file" class="form-label">{{ __('Select Backup File') }}</label>
                        <input type="file" class="form-control" id="backup_file" name="backup_file" accept=".zip" required>
                        <small class="text-muted">{{ __('Only ZIP files are supported') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-warning">{{ __('Import') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
