<div class="container">
    <div class="row mt-5 flex-container">
        @forelse($categories as $index => $category)
        <div class="col-md-3">
            <a href="{{ route('folder.index', ['group' => $category->default_group_id]) }}" class="card-link"
                style="text-decoration: none;">
                <div class="card mb-3 height-100 bg-grey-100">
                    <div class="card-body flex-container">
                        <h5 class="card-title">{{ $index + 1 }}. {{ $category->name }}</h5>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <p>No categories found.</p>
        @endforelse
    </div>
</div>