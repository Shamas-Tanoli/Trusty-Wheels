@php
$configData = Helper::appClasses();
use Illuminate\Support\Facades\DB;
@endphp

@extends('admin/layouts/layoutMaster')

@section('title', 'Home')

@section('content')

@include('admin.content.pages.adminHome')

<div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Latest Inquiries</h5>
                <a href="{{ route('customer.inquiry') }}" class="btn btn-sm btn-primary">View All</a>
        </div>

        <div class="card-body p-0">
                <table class="table table-striped mb-0">
                        <thead class="table-light">
                                <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Pessangers</th>
                                        <th>Date</th>
                                </tr>
                        </thead>
                        <tbody>
                                @forelse($latestInquiries as $key => $inquiry)
                                <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $inquiry->name ?? '-' }}</td>
                                        <td>{{ $inquiry->contact ?? '-' }}</td>
                                        <td>{{ $inquiry->contact ?? '-' }}</td>
                                        <td>
                                                <div>{{ $inquiry->created_at->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $inquiry->created_at->format('h:i A')
                                                        }}</small>
                                        </td>
                                </tr>
                                @empty
                                <tr>
                                        <td colspan="5" class="text-center">No inquiries found</td>
                                </tr>
                                @endforelse
                        </tbody>
                </table>
        </div>
</div>
@endsection