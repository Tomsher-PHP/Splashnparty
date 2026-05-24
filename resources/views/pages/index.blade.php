@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-4">Page Management</h6>
            <p class="mb-0 text-secondary-light">Manage dynamic layouts and content for various pages of the website.</p>
        </div>
    </div>

    <div class="row gy-4">
        @forelse ($pages as $page)
            <div class="col-xl-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 page-card-hover">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center gap-16 mb-20">
                            <div class="page-card-icon bg-primary-50 text-primary-600 rounded-12 d-flex align-items-center justify-content-center">
                                <i class="ri-pages-line text-2xl"></i>
                            </div>
                            <div>
                                <h6 class="text-md fw-semibold mb-4 text-dark">{{ $page->title }}</h6>
                                {{-- <span class="bg-neutral-100 text-neutral-600 px-12 py-4 rounded-pill fw-medium text-xs">
                                    Slug: {{ $page->slug }}
                                </span> --}}
                            </div>
                        </div>
{{--                         
                        <p class="text-secondary-light mb-24 text-sm">
                            Configure content fields, custom sections, image galleries, and repeatable lists on the {{ $page->title }} page.
                        </p> --}}

                        <div class="d-flex align-items-center justify-content-between pt-16 border-top border-neutral-100">
                            <span class="text-secondary-light text-xs fw-medium">
                                Last updated: {{ $page->updated_at ? $page->updated_at->diffForHumans() : 'Never' }}
                            </span>
                            @can('edit_pages')
                                <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-sm btn-primary-600 d-inline-flex align-items-center gap-2">
                                    <i class="ri-edit-line"></i>
                                    Manage Content
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card p-48 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-neutral-400 mb-16">
                            <i class="ri-pages-line text-5xl"></i>
                        </div>
                        <h6 class="fw-semibold text-dark mb-8">No Pages Available</h6>
                        <p class="text-secondary-light mb-0">There are no dynamic pages configured in `config/pages.php` yet.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@section('style')
    <style>
        .page-card-icon {
            width: 56px;
            height: 56px;
            transition: all 0.3s ease;
        }

        .page-card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px !important;
            overflow: hidden;
        }

        .page-card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08) !important;
        }

        .page-card-hover:hover .page-card-icon {
            background-color: var(--primary-600) !important;
            color: #fff !important;
        }
    </style>
@endsection
