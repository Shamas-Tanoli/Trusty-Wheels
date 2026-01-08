@extends('admin/layouts/layoutMaster')

@section('title', 'Admin Profile - Edit')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
'resources/assets/admin/vendor/libs/@form-validation/form-validation.scss',
'resources/assets/admin/vendor/libs/sweetalert2/sweetalert2.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
'resources/assets/admin/vendor/libs/@form-validation/popular.js',
'resources/assets/admin/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/admin/vendor/libs/@form-validation/auto-focus.js',
'resources/assets/admin/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/admin/js/admin-profile-edit.js'])
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-6">
            <!-- Account -->

            <div class="card-body pt-4">
                <form id="formAccountSettings" method="post">
                    <div class="row">
                        <div class="mb-4 col-md-4">
                            <label for="Name" class="form-label">Name</label>
                            <input class="form-control" type="text" id="Name" name="name" value="{{ $user->name }}" autofocus />
                        </div>
                
                        <div class="mb-4 col-md-4">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="email" id="email" name="email" value="{{ $user->email }}" placeholder="john.doe@example.com" />
                        </div>
                
                        <div class="mb-4 col-md-4 form-password-toggle">
                            <label class="form-label" for="currentPassword">Current Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" name="currentPassword" id="currentPassword" placeholder="••••••••" />
                                <span class="input-group-text cursor-pointer toggle-password" data-target="currentPassword"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                
                        <div class="mb-4 col-md-4 form-password-toggle">
                            <label class="form-label" for="newPassword">New Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="••••••••" />
                                <span class="input-group-text cursor-pointer toggle-password" data-target="newPassword"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                
                        <div class="mb-4 col-md-4 form-password-toggle">
                            <label class="form-label" for="confirmPassword">Confirm New Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="••••••••" />
                                <span class="input-group-text cursor-pointer toggle-password" data-target="confirmPassword"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                        <button type="reset" class="btn btn-label-secondary">Reset</button>
                    </div>
                </form>
                
            </div>
            <!-- /Account -->
        </div>

    </div>
</div>

@endsection