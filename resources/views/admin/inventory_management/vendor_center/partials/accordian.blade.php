@php
    $collapseId = 'collapse-' . $category->id;
    $headingId = 'heading-' . $category->id;
@endphp

<div class="accordion border" id="accordion-{{ $category->id }}">
    <div class="card mb-0 overflow-visible">
        <div class="card-header d-flex justify-content-between align-items-center" id="{{ $headingId }}">
            <h2 class="mb-0 w-100">
                <button class="btn btn-link btn-block text-left text-start p-3 bg-gray-100 fs-6" type="button"
                    data-toggle="collapse" data-target="#{{ $collapseId }}" aria-expanded="true"
                    aria-controls="{{ $collapseId }}">
                    {{ $category->code . ' - ' . $category->name }}
                </button>
            </h2>

            <div class="dropdown position-absolute" style="top: 10px; right: 15px;">
                <button class="py-2 btn btn-sm btn-secondary dropdown-toggle" type="button"
                    id="dropdownMenu{{ $category->id }}" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu{{ $category->id }}">
                    @if ($category->level < 3)
                        <button class="dropdown-item create-btn" data-id="{{ $category->id }}">
                            <i class="fas fa-plus mr-1"></i> Create
                        </button>
                    @endif
                    @if ($category->level > 1)
                        <button class="dropdown-item edit-btn" data-id="{{ $category->id }}">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <form action="{{ route('inventory.vendor-category.destroy', $category->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger delete-btn">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div id="{{ $collapseId }}" class="collapse border border-card p-3" aria-labelledby="{{ $headingId }}"
            data-parent="#accordion-{{ $category->id }}">
            <div class="card-body bg-white">

                {{-- Show vendors only if this is a leaf node --}}
                @if ($category->recursiveChildren->isEmpty() && $category->vendors->isNotEmpty())
                        <ul class="list-group">
                            @foreach ($category->vendors as $vendor)
                                <li>
                                    <div>
                                        <strong>{{ $vendor->code. ' - ' . $vendor->name }}</strong>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                @endif

                {{-- Render child categories --}}
                @foreach ($category->recursiveChildren as $child)
                    @include('admin.inventory_management.vendor_center.partials.accordian', ['category' => $child])
                @endforeach
            </div>
        </div>
    </div>
</div>
