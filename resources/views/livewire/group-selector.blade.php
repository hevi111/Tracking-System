<div class="container mt-5 mb-5">
    @if ($showCreateForm || $editMode)
    <div class="card bg-body-secondary rounded p-4 mb-5">
        <div class="card-body">
            <h3 class="card-title mb-3">{{ $editMode ? 'Edit Folder' : 'Create Folder' }}</h3>
            <form wire:submit.prevent="{{ $editMode ? 'updateFolder' : 'createFolder' }}">
                <div class="mb-3">
                    <input wire:model="{{ $editMode ? 'editFolderName' : 'newFolderName' }}" type="text"
                        class="form-control" id="{{ $editMode ? 'editFolderName' : 'folderName' }}"
                        placeholder="{{ $editMode ? 'Edit Folder Name' : 'Create Folder Name' }}">
                    @if ($editMode)
                    @error('editFolderName') <span class="text-danger">{{ $message }}</span> @enderror
                    @else
                    @error('newFolderName') <span class="text-danger">{{ $message }}</span> @enderror
                    @endif
                </div>
                <div class="d-flex justify-content-between">
                    <div></div>
                    <div class="d-flex">
                        <button style="margin-right: 10px" type="submit"
                            class="btn btn-{{ $editMode ? 'warning' : 'success' }}">{{ $editMode ? 'Update' : 'Create' }}</button>
                        <button wire:click="{{ $editMode ? 'cancelEdit' : 'closeCreateForm' }}"
                            class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if ($uploadMode)
    <div class="card bg-body-secondary rounded p-4 mb-5">
        <div class="card-body">
            <h3 class="card-title mb-4">Upload Files</h3>
            <form wire:submit="uploadFile">
                <input type="file" wire:model="file">
                @error('file') <span class="error">{{ $message }}</span> @enderror
                <div class="d-flex justify-content-between">
                    <div></div>
                    <div class="d-flex align-items-center mt-3">
                        <button wire:target="uploadFile" wire:loading.attr="disabled" type="submit"
                            style="margin-right: 10px" class="btn btn-success">
                            <span wire:target="uploadFile" wire:loading.remove>Save file</span>
                            <span wire:target="uploadFile" wire:loading>Saving...</span>
                        </button>
                        <button wire:click="closeUploadForm" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @endif

    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
            </li>
            @foreach(array_reverse($navigation) as $item)
            <li class="breadcrumb-item"><a href="{{ route('folder.index', $item['id']) }}">{{ $item['name'] }}</a>
            </li>
            @endforeach
        </ol>
    </nav>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ $group->name }}</h2>
            <div class="btn-group">
                <button class="btn btn-success btn-create-folder" data-bs-toggle="dropdown">
                    <i class="fas fa-plus"></i> Create
                </button>
                <div class="dropdown-menu">
                    <a wire:click="openCreateForm" class="dropdown-item" href="#">Create Folder</a>
                    <a wire:click="openUploadForm" class="dropdown-item" href="#">Upload Files</a>
                </div>
            </div>
        </div>
        @forelse($groups as $index => $group)
        <div class="table-row d-flex align-items-center" wire:key="{{ $index }}">
            <a href="{{ route('folder.index', $group->id) }}" class="folder-link">
                <div class="file-info">
                    <div class="d-flex align-items-center">
                        <div class="folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <h5 class="card-title">{{ $index + 1 }}. {{ $group->name }}</h5>
                    </div>
                    <h6 class="card-title">
                        {{ $group->created_at->diffForHumans() }}
                    </h6>
                </div>
            </a>
            <div class="d-flex">
                <button wire:click="editFolder({{ $group->id }})" class="btn btn-warning"
                    style="margin-right: 10px; margin-left: 30px;">Edit</button>

                <button wire:click="triggerDeleteItem({{ $group->id }})" class="btn btn-danger"
                    style="margin-right: 10px;">Delete</button>

            </div>
        </div>
        @empty
        <p>No groups found.</p>
        @endforelse

        <br>

        @foreach($mediaList as $index => $item)
        <div class="table-row d-flex align-items-center" wire:key="{{ $index }}">
            <a href="{{ $item->getFullUrl() }}" class="folder-link">
                <div class="file-info">
                    <div class="d-flex align-items-center">
                        <div class="folder-icon">
                            <i class="fas fa-file"></i>
                        </div>
                        <h5 class="card-title">{{ $index + 1 }}. {{ $item->file_name }}</h5>
                    </div>
                    <h6 class="card-title">
                        <span style="margin-right: 30px;">{{ $item->human_readable_size }}</span>
                        <span style="margin-right: 30px;">{{ $item->created_at->diffForHumans() }}</span>
                    </h6>
                </div>
            </a>
            <div class=" d-flex">
                <a href="{{  $item->getFullUrl() }}" class="btn btn-warning" style="margin-right: 10px;" download>
                    Download </a>
                <button wire:click="triggerDeleteFile('{{ $item->uuid }}')" class="btn btn-danger"
                    style="margin-right: 10px;">Delete</button>
            </div>
        </div>
        @endforeach
    </div>
</div>