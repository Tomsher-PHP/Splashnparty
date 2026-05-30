@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-32">
    <div>
        <h5 class="fw-bold mb-4 modern-title">Header Navigation</h5>
        {{-- <p class="mb-0 text-secondary-light text-sm">Sort links vertically, and use the <strong>Left/Right arrows</strong> to indent items dynamically into sub-menu dropdowns.</p> --}}
    </div>
</div>

<div class="row gy-4">
    <!-- Left Column: Flat Nestable List -->
    <div class="col-lg-8">
        <div class="card modern-card border-0 shadow-lg">
            <div class="card-header modern-card-header d-flex justify-content-between align-items-center py-20 px-24">
                <div class="d-flex align-items-center gap-2">
                    <span class="modern-header-icon"><i class="ri-node-tree"></i></span>
                    <h6 class="text-md fw-bold mb-0 text-dark">Header Menu</h6>
                </div>
                <span id="saveStatusIndicator" class="badge bg-success-focus text-success px-12 py-6 rounded-pill text-xs d-inline-flex align-items-center gap-1 transition-all" style="opacity: 0.8;">
                    <i class="ri-checkbox-circle-fill"></i> Saved
                </span>
            </div>
            
            <div class="card-body p-24 bg-pattern">
                <div id="menu-tree" class="nested-sortable-root">
                    @forelse($headerMenus as $menu)
                        <div class="menu-group-wrapper" data-id="{{ $menu->id }}">
                            <!-- Parent Node -->
                            <div class="menu-item-card parent-node bg-white border rounded-16 p-10 mb-12 shadow-sm position-relative transition-all d-flex align-items-center justify-content-between gap-3" 
                                data-id="{{ $menu->id }}" style="border-left: 5px solid var(--primary-600) !important;">
                                
                                <div class="d-flex align-items-center gap-3">
                                    @can('edit_header_menus')
                                    <span class="drag-handle text-secondary-light" style="cursor: grab; font-size: 20px;">
                                        <i class="ri-drag-move-2-line"></i>
                                    </span>
                                    @endcan
                                    <div>
                                        <div class="d-flex align-items-center flex-wrap gap-2 mb-4">
                                            <span class="fw-bold text-dark text-sm menu-title-text">{{ $menu->title }}</span>
                                            {{-- <span class="badge badge-premium-primary text-xs px-10 py-2 rounded-pill type-badge">Main Menu</span> --}}
                                            @if(!$menu->status)
                                                <span class="badge badge-premium-danger text-xs px-10 py-2 rounded-pill">Inactive</span>
                                            @endif
                                            <span class="text-muted text-xs d-flex align-items-center gap-1">
                                                <i class="ri-link text-xs"></i> {{ $menu->url ?: '/' }}
                                            </span>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-2">
                                        @can('edit_header_menus')
                                        <button type="button" 
                                            class="modern-icon-btn edit-menu-btn text-success bg-success-focus bg-hover-success-200 w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                            data-id="{{ $menu->id }}"
                                            data-title="{{ $menu->title }}"
                                            data-url="{{ $menu->url }}"
                                            data-parent-id="{{ $menu->parent_id }}"
                                            data-sort-order="{{ $menu->sort_order }}"
                                            data-status="{{ $menu->status }}"
                                            data-update-url="{{ route('header-menus.update', $menu) }}"
                                            title="Edit Item">
                                            <iconify-icon icon="lucide:edit" class="menu-icon text-sm"></iconify-icon>
                                        </button>
                                        @endcan
                                        @can('delete_header_menus')
                                        <form action="{{ route('header-menus.destroy', $menu) }}" method="POST" class="delete-menu-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="modern-icon-btn text-danger bg-danger-focus bg-hover-danger-200 w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                data-confirm-title="Delete Menu" 
                                                data-confirm-message="Are you sure you want to delete '{{ $menu->title }}'?" 
                                                title="Delete Item">
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon text-sm"></iconify-icon>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>

                            {{-- @if($menu->children->count() > 0) --}}
                            <!-- Children Nested Wrapper -->
                            <div class="nested-menu-list" data-parent-id="{{ $menu->id }}" style="margin-left: 40px; min-height: 15px;">
                                @foreach($menu->children as $child)
                                    <div class="menu-item-card child-node is-child bg-white border rounded-16 p-10 mb-12 shadow-sm position-relative transition-all d-flex align-items-center justify-content-between gap-3" 
                                        data-id="{{ $child->id }}" style="border-left: 4px solid #cbd5e1 !important; background-color: rgba(248, 250, 252, 0.5) !important;">
                                        
                                        <div class="d-flex align-items-center gap-3">
                                            @can('edit_header_menus')
                                            <span class="drag-handle text-secondary-light" style="cursor: grab; font-size: 20px;">
                                                <i class="ri-drag-move-2-line"></i>
                                            </span>
                                            @endcan
                                            <div>
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-4">
                                                    <span class="fw-semibold text-secondary text-sm menu-title-text"><i class="ri-corner-down-right-line text-primary me-4"></i> {{ $child->title }}</span>
                                                    {{-- <span class="badge badge-premium-secondary text-xs px-8 py-2 rounded-pill type-badge">Sub-menu</span> --}}
                                                    @if(!$child->status)
                                                        <span class="badge badge-premium-danger text-xs px-8 py-2 rounded-pill">Inactive</span>
                                                    @endif
                                                    <span class="text-muted text-xs d-flex align-items-center gap-1">
                                                        <i class="ri-link text-xxs"></i> {{ $child->url }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center gap-2">
                                                @can('edit_header_menus')
                                                <button type="button" 
                                                    class="modern-icon-btn edit-menu-btn text-success bg-success-focus bg-hover-success-200 w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                    data-id="{{ $child->id }}"
                                                    data-title="{{ $child->title }}"
                                                    data-url="{{ $child->url }}"
                                                    data-parent-id="{{ $child->parent_id }}"
                                                    data-sort-order="{{ $child->sort_order }}"
                                                    data-status="{{ $child->status }}"
                                                    data-update-url="{{ route('header-menus.update', $child) }}"
                                                    title="Edit Item">
                                                    <iconify-icon icon="lucide:edit" class="menu-icon text-sm"></iconify-icon>
                                                </button>
                                                @endcan
                                                @can('delete_header_menus')
                                                <form action="{{ route('header-menus.destroy', $child) }}" method="POST" class="delete-menu-form d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="modern-icon-btn text-danger bg-danger-focus bg-hover-danger-200 w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0" 
                                                        data-confirm-title="Delete Sub-Menu" 
                                                        data-confirm-message="Are you sure you want to delete sub-menu '{{ $child->title }}'?" 
                                                        title="Delete Item">
                                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon text-sm"></iconify-icon>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- @endif --}}
                        </div>
                    @empty
                        <div class="text-center py-64 text-secondary-light bg-white rounded-16 border shadow-sm w-100">
                            <i class="ri-menu-2-line text-5xl d-block mb-16 gradient-text"></i>
                            <h6 class="fw-bold text-dark mb-4">No navigation links defined yet</h6>
                            <p class="text-sm text-secondary-light mb-0">Use the designer panel on the right to populate your navigation.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Add Menu Item Form -->
    <div class="col-lg-4">
        <div class="card modern-card border-0 shadow-lg position-sticky" style="top: 24px;">
            <div class="card-header modern-card-header py-20 px-24 border-bottom bg-gradient-premium">
                <div class="d-flex align-items-center gap-2">
                    <span class="modern-header-icon text-white"><i class="ri-add-circle-fill"></i></span>
                    <h6 class="text-md fw-bold mb-0 text-white">Add Menu Node</h6>
                </div>
            </div>
            
            <div class="card-body p-24">
                @can('create_header_menus')
                <form action="{{ route('header-menus.store') }}" method="POST" class="modern-form">
                    @csrf
                    
                    <div class="mb-20">
                        <label for="title" class="form-label fw-bold text-secondary text-xs text-uppercase tracking-wider">Label / Title <span class="text-danger">*</span></label>
                        <div class="input-group-modern">
                            <span class="input-icon"><i class="ri-bookmark-line"></i></span>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="form-control form-control-sm @error('title') is-invalid @enderror" placeholder="e.g. Home, About Us">
                        </div>
                        @error('title')
                            <div class="invalid-feedback d-block mt-4">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-20">
                        <label for="url" class="form-label fw-bold text-secondary text-xs text-uppercase tracking-wider">Link / URL</label>
                        <div class="input-group-modern">
                            <span class="input-icon"><i class="ri-link"></i></span>
                            <input type="text" id="url" name="url" value="{{ old('url') }}"
                                class="form-control form-control-sm @error('url') is-invalid @enderror" placeholder="e.g. /, /about-us">
                        </div>
                        @error('url')
                            <div class="invalid-feedback d-block mt-4">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-20">
                        <label for="parent_id" class="form-label fw-bold text-secondary text-xs text-uppercase tracking-wider">Parent Menu</label>
                        <div class="input-group-modern">
                            <span class="input-icon"><i class="ri-folder-open-line"></i></span>
                            <select id="parent_id" name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">-- Main Menu Item (No Parent) --</option>
                                @foreach ($headerMenus as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('parent_id')
                            <div class="invalid-feedback d-block mt-4">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-20">
                        <label for="sort_order" class="form-label fw-bold text-secondary text-xs text-uppercase tracking-wider">Sort Order <span class="text-danger">*</span></label>
                        <div class="input-group-modern">
                            <span class="input-icon"><i class="ri-sort-asc"></i></span>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required
                                class="form-control form-control-sm @error('sort_order') is-invalid @enderror">
                        </div>
                        @error('sort_order')
                            <div class="invalid-feedback d-block mt-4">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-32">
                        <label for="status" class="form-label fw-bold text-secondary text-xs text-uppercase tracking-wider">Status <span class="text-danger">*</span></label>
                        <div class="input-group-modern">
                            <span class="input-icon"><i class="ri-toggle-line"></i></span>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', '1') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        @error('status')
                            <div class="invalid-feedback d-block mt-4">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-premium w-100 d-inline-flex align-items-center justify-content-center gap-2 py-14 rounded-12">
                        <i class="ri-add-line text-lg"></i> Create Menu Node
                    </button>
                </form>
                @else
                <div class="alert alert-warning text-sm mb-0 rounded-12">
                    You do not have permission to add new menu items.
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DELETE ACTIONS WITH APP CONFIRM
        document.querySelectorAll('.delete-menu-form button[type="submit"]').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const form = button.closest('form');

                window.openAppConfirm({
                    title: button.dataset.confirmTitle || 'Delete',
                    message: button.dataset.confirmMessage || 'Are you sure you want to continue?',
                    buttonText: 'Yes, Delete',
                    buttonClass: 'btn btn-sm btn-danger rounded-pill px-16',
                    onConfirm: function() {
                        form.submit();
                    }
                });
            });
        });

        @can('edit_header_menus')
        // JQUERY UI NESTED DRAGGABLE INITIALIZATION
        function initJQueryUISortables() {
            // First destroy existing sortable instances to avoid connection/state bugs
            try {
                if ($("#menu-tree").data("ui-sortable")) {
                    $("#menu-tree").sortable("destroy");
                }
            } catch(e) {}
            try {
                $(".nested-menu-list").each(function() {
                    if ($(this).data("ui-sortable")) {
                        $(this).sortable("destroy");
                    }
                });
            } catch(e) {}

            // Initialize JQuery UI Sortable on root and all active sub-lists
            $("#menu-tree, .nested-menu-list").sortable({
                connectWith: "#menu-tree, .nested-menu-list",
                handle: ".drag-handle",
                placeholder: "sortable-placeholder bg-light border border-dashed rounded-16 mb-12",
                tolerance: "pointer",
                cursor: "move",
                start: function(event, ui) {
                    ui.placeholder.height(ui.item.outerHeight());
                    ui.placeholder.css({
                        'min-height': '50px',
                        'margin-bottom': '12px',
                        'border': '2px dashed var(--primary-600)',
                        'background-color': 'var(--primary-light)'
                    });
                },
                stop: function(event, ui) {
                    const item = ui.item[0];
                    const parent = ui.item.parent()[0];
                    
                    if (!parent) return;

                    if (parent.id === 'menu-tree') {
                        // Dragged out of sublist directly to root
                        if (!item.classList.contains('menu-group-wrapper')) {
                            setTimeout(function() {
                                // Wrap the card inside a new group wrapper
                                const wrapper = $('<div class="menu-group-wrapper" data-id="' + item.getAttribute('data-id') + '"></div>');
                                $(item).before(wrapper);
                                wrapper.append(item);
                                
                                const sublist = $('<div class="nested-menu-list mb-24" data-parent-id="' + item.getAttribute('data-id') + '" style="margin-left: 40px; min-height: 15px;"></div>');
                                wrapper.append(sublist);
                                
                                updateDragItemStyles(item, parent);
                                
                                // Re-initialize and reconnect all sortable zones
                                initJQueryUISortables();
                                autoSaveStructure();
                            }, 0);
                        } else {
                            autoSaveStructure();
                        }
                    } else if (parent.classList.contains('nested-menu-list')) {
                        // Dragged into a child sublist!
                        if (item.classList.contains('menu-group-wrapper')) {
                            setTimeout(function() {
                                // A parent wrapper was dragged into a child sublist!
                                const card = $(item).find('.menu-item-card').first();
                                const children = $(item).find('.nested-menu-list .menu-item-card');
                                
                                // Insert the card itself in place of the wrapper
                                $(item).before(card);
                                
                                // Insert its children as siblings in the same sublist
                                if (children.length > 0) {
                                    $(item).after(children);
                                }
                                
                                // Remove the empty wrapper
                                $(item).remove();
                                
                                // Update card styles to Child Node
                                updateDragItemStyles(card[0], parent);
                                
                                // Update children styles just in case
                                children.each(function() {
                                    updateDragItemStyles(this, parent);
                                });
                                
                                // Re-initialize and reconnect all sortable zones
                                initJQueryUISortables();
                                autoSaveStructure();
                            }, 0);
                        } else {
                            updateDragItemStyles(item, parent);
                            autoSaveStructure();
                        }
                    } else {
                        autoSaveStructure();
                    }
                }
            }).disableSelection();
        }

        // Initialize Sortable on load
        initJQueryUISortables();

        // Dynamically update styles when transforming between main and child node
        function updateDragItemStyles(item, to) {
            const cardEl = $(item);
            const isRoot = (to.id === 'menu-tree' || to.classList.contains('nested-sortable-root'));
            
            const badge = cardEl.find('.type-badge');
            const titleEl = cardEl.find('.menu-title-text');
            const editBtn = cardEl.find('.edit-menu-btn');
            
            if (isRoot) {
                // Main Menu Node style
                cardEl.removeClass('child-node is-child').addClass('parent-node');
                cardEl.css({
                    'margin-left': '0',
                    'border-left': '5px solid var(--primary-600)',
                    'background-color': '#fff'
                });
                
                badge.removeClass('badge-premium-secondary').addClass('badge-premium-primary').text('Main Menu');
                titleEl.removeClass('fw-semibold text-secondary').addClass('fw-bold text-dark');
                titleEl.find('.ri-corner-down-right-line').remove();
                
                // Clear parent in data attributes
                editBtn.attr('data-parent-id', '');
                editBtn.data('parent-id', '');
            } else {
                // Sub-menu Node style
                cardEl.removeClass('parent-node').addClass('child-node is-child');
                cardEl.css({
                    'margin-left': '0', // Margin handled by sublist container
                    'border-left': '4px solid #cbd5e1',
                    'background-color': 'rgba(248, 250, 252, 0.5)'
                });
                
                badge.removeClass('badge-premium-primary').addClass('badge-premium-secondary').text('Sub-menu');
                titleEl.removeClass('fw-bold text-dark').addClass('fw-semibold text-secondary');
                if (titleEl.find('.ri-corner-down-right-line').length === 0) {
                    titleEl.prepend('<i class="ri-corner-down-right-line text-primary me-4"></i>');
                }
                
                // Set parent in data attributes
                const newParentId = $(to).attr('data-parent-id');
                editBtn.attr('data-parent-id', newParentId);
                editBtn.data('parent-id', newParentId);
            }
        }

        // AUTO-SAVE HANDLER
        function autoSaveStructure() {
            const structure = [];
            const indicator = $('#saveStatusIndicator');
            
            indicator.html('<i class="ri-loader-4-line animate-spin"></i> Saving changes...').removeClass('bg-success-focus text-success').addClass('bg-warning-focus text-warning');

            document.querySelectorAll('#menu-tree > .menu-group-wrapper').forEach(function(groupEl, parentIndex) {
                const parentCard = groupEl.querySelector('.parent-node');
                if (!parentCard) return;
                
                const parentId = parentCard.getAttribute('data-id');
                const childrenIds = [];
                
                groupEl.querySelectorAll('.nested-menu-list .menu-item-card').forEach(function(childCard) {
                    childrenIds.push(childCard.getAttribute('data-id'));
                });
                
                structure.push({
                    id: parentId,
                    children: childrenIds
                });
            });

            $.ajax({
                url: "{{ route('header-menus.reorder') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    structure: structure
                },
                success: function(response) {
                    setTimeout(function() {
                        indicator.html('<i class="ri-checkbox-circle-fill"></i> Saved').removeClass('bg-warning-focus text-warning').addClass('bg-success-focus text-success');
                    }, 500);
                },
                error: function(xhr) {
                    indicator.html('<i class="ri-error-warning-fill"></i> Save Failed').removeClass('bg-warning-focus text-warning').addClass('bg-danger-focus text-danger');
                    console.error('Auto-save failed', xhr);
                }
            });
        }

        // DYNAMIC INLINE EDIT FORM SWITCHER LOGIC
        const rightForm = $('.modern-form');
        const formHeader = rightForm.closest('.card').find('.modern-card-header');
        const formTitle = formHeader.find('h6');
        const formHeaderIcon = formHeader.find('.modern-header-icon i');
        const submitBtn = rightForm.find('button[type="submit"]');
        const originalAction = rightForm.attr('action');
        let cancelBtn = $('<button type="button" class="btn btn-outline-secondary w-100 py-14 rounded-12 mt-3" id="cancelEditBtn" style="border: 1.5px solid #cbd5e1; font-weight: 600;"><i class="ri-close-line text-lg"></i> Cancel Edit</button>');

        $(document).on('click', '.edit-menu-btn', function(e) {
            e.preventDefault();
            
            const btn = $(this);
            const id = btn.data('id');
            const title = btn.data('title');
            const url = btn.data('url');
            const parentId = btn.data('parent-id');
            const sortOrder = btn.data('sort-order');
            const status = btn.data('status');
            const updateUrl = btn.data('update-url');

            // Switch to Edit Mode style (Emerald color scheme)
            formTitle.text('Edit Menu Node: ' + title);
            formHeader.removeClass('bg-gradient-premium').css('background', 'linear-gradient(135deg, #10b981 0%, #059669 100%)');
            formHeaderIcon.removeClass('ri-add-circle-fill').addClass('ri-edit-box-fill');

            // Update form properties
            rightForm.attr('action', updateUrl);
            if (rightForm.find('input[name="_method"]').length === 0) {
                rightForm.prepend('<input type="hidden" name="_method" value="PUT">');
            }

            // Populate fields
            rightForm.find('#title').val(title);
            rightForm.find('#url').val(url || '');
            rightForm.find('#sort_order').val(sortOrder);
            rightForm.find('#status').val(status);

            // Populate parent select, and disable own node option to prevent self-parenting loop
            const selectParent = rightForm.find('#parent_id');
            selectParent.find('option').prop('disabled', false);
            selectParent.find(`option[value="${id}"]`).prop('disabled', true);
            selectParent.val(parentId || "");

            // Update submit button
            submitBtn.removeClass('btn-premium').addClass('btn-success').css('background', 'linear-gradient(135deg, #10b981 0%, #059669 100%)').html('<i class="ri-check-line text-lg"></i> Update Menu Node');

            // Add cancel button if not present
            if ($('#cancelEditBtn').length === 0) {
                submitBtn.after(cancelBtn);
            }

            // Scroll to the card form smoothly
            $('html, body').animate({
                scrollTop: rightForm.closest('.card').offset().top - 40
            }, 300);
        });

        // Cancel Edit Mode
        $(document).on('click', '#cancelEditBtn', function() {
            // Reset header style
            formTitle.text('Add Menu Node');
            formHeader.addClass('bg-gradient-premium').css('background', '');
            formHeaderIcon.removeClass('ri-edit-box-fill').addClass('ri-add-circle-fill');

            // Reset form properties
            rightForm.attr('action', originalAction);
            rightForm.find('input[name="_method"]').remove();

            // Clear values
            rightForm.find('#title').val('');
            rightForm.find('#url').val('');
            rightForm.find('#sort_order').val('0');
            rightForm.find('#status').val('1');

            // Reset parent selection
            const selectParent = rightForm.find('#parent_id');
            selectParent.find('option').prop('disabled', false);
            selectParent.val('');

            // Restore submit button
            submitBtn.removeClass('btn-success').addClass('btn-premium').css('background', '').html('<i class="ri-add-line text-lg"></i> Create Menu Node');

            // Remove cancel button
            $('#cancelEditBtn').remove();
        });
        @endcan
    });
</script>
@endsection

@section('style')
<style>
    /* Premium Modern Design */
    .modern-title {
        background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }
    
    .modern-card {
        border-radius: 20px !important;
        overflow: hidden;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .modern-card-header {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
    }
    
    .bg-gradient-premium {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%) !important;
    }
    
    .modern-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--primary-light);
        color: var(--primary-600);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    .bg-gradient-premium .modern-header-icon {
        background: rgba(255, 255, 255, 0.2);
    }
    
    /* Nestable Nodes UI */
    .menu-item-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .menu-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.06) !important;
    }
    
    .menu-group-wrapper {
        display: block;
        width: 100%;
        margin-bottom: 8px;
    }
    
    .nested-menu-list {
        min-height: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 4px;
        border-radius: 16px;
        margin-top: -6px;
    }
    
    .nested-menu-list:empty {
        min-height: 10px;
        margin-bottom: 0px;
        padding: 0;
    }
    
    /* SortableJS Ghost states */
    .sortable-ghost {
        opacity: 0.35;
        background-color: var(--primary-light) !important;
        border: 2px dashed var(--primary-600) !important;
        transform: scale(0.98);
    }
    .sortable-chosen {
        background-color: #fff !important;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04) !important;
    }
    .sortable-drag {
        opacity: 0.8;
    }
    
    /* Button Premium Gradient */
    .btn-premium {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        border: none;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 4px 12px var(--primary-light);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-premium:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px var(--primary-light-white);
        background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-800) 100%);
    }
    .btn-premium:active {
        transform: translateY(1px);
    }
    
    .pulse-glow {
        animation: pulseGlow 2s infinite;
    }
    
    @keyframes pulseGlow {
        0% {
            box-shadow: 0 0 0 0 var(--primary-light);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
        }
        100% {
            box-shadow: 0 0 0 0 var(--primary-light);
        }
    }
    
    /* Premium Modern Form Controls */
    .input-group-modern {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }
    
    .input-group-modern .,
    .input-group-modern .form-select {
        padding-left: 44px !important;
        height: 46px !important;
        border-radius: 12px !important;
        border: 1.5px solid #e2e8f0 !important;
        background-color: #fff !important;
        font-size: 14px !important;
        transition: all 0.3s;
    }
    
    .input-group-modern .form-control:focus,
    .input-group-modern .form-select:focus {
        border-color: var(--primary-600) !important;
        box-shadow: 0 0 0 4px var(--primary-light) !important;
    }
    
    .input-icon {
        position: absolute;
        left: 16px;
        color: #94a3b8;
        font-size: 18px;
        z-index: 4;
        pointer-events: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .input-group-modern .form-control:focus ~ .input-icon,
    .input-group-modern .form-select:focus ~ .input-icon {
        color: var(--primary-600);
    }
    
    /* Rounded Pill Badges */
    .badge-premium-primary {
        background-color: var(--primary-light);
        color: var(--primary-600);
        font-weight: 600;
    }
    
    .badge-premium-secondary {
        background-color: rgba(148, 163, 184, 0.12);
        color: #475569;
        font-weight: 600;
    }
    
    .badge-premium-danger {
        background-color: rgba(239, 68, 68, 0.12);
        color: #ef4444;
        font-weight: 600;
    }
    
    /* Modern Icon Buttons */
    .modern-icon-btn, .btn-indent-outdent {
        transition: all 0.2s ease-in-out;
    }
    .modern-icon-btn:hover {
        transform: scale(1.1);
    }
    
    .btn-indent-outdent {
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-indent-outdent:not(.disabled):hover {
        background-color: var(--primary-light) !important;
        color: var(--primary-600) !important;
    }
    .btn-indent-outdent.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    
    /* Gradient Icons */
    .gradient-text {
        background: linear-gradient(135deg, var(--primary-600) 0%, #ec4899 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection
