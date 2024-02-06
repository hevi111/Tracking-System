<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Group;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;

class GroupSelector extends Component
{
    use LivewireAlert, WithFileUploads;
    public $group;
    public $groups;
    public $showCreateForm = false;
    public string $newFolderName = "";
    public array $navigation = [];

    public $mediaList;
    public $file;

    public $editMode = false;
    public $editFolderId;
    public $editFolderName;

    public $uploadMode = false;

    protected $listeners = [
        'deleteItem',
        'deleteFile',
    ];

    public int $itemIdToBeDeleted = 0;
    public string $fileIdToBeDeleted = '';



    public function openUploadForm() {
        $this->uploadMode = true;
    }

    public function closeUploadForm() {
        $this->uploadMode = false;
    }

    public function uploadFile() {

        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:102400', // 100MB Max
        ]);
    
        $this->group->addMedia($this->file)
            ->toMediaCollection('files');
    
        $this->file = null;
        $this->uploadMode = false;
    }


    public function triggerDeleteItem($item): void
    {
        $this->confirm('Are you sure that you want to delete this item?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Cancel',
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'deleteItem'
        ]);

        $this->itemIdToBeDeleted = $item;
    }

    
    public function deleteItem(): void
    {
        $group = Group::find($this->itemIdToBeDeleted);
        
        if($group) {
            
            $group->delete();
        }
        
        $this->alert('success', 'Item successfully deleted.', [
            'position' => 'top-end',
            'timer' => 5000,
            'toast' => true,
        ]);
    }

    public function triggerDeleteFile($uuid): void {
        $this->confirm('Are you sure that you want to delete this file?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => 'Cancel',
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'deleteFile'
        ]);

        $this->fileIdToBeDeleted = $uuid;
    }

    public function deleteFile(): void {
        $media = $this->group->getMedia('files')->where('uuid', $this->fileIdToBeDeleted)->first();
        if ($media) {
            $media->delete();
            $this->group = Group::find($this->group->id);
        }
    }

    public function mount(Group $group) {
        
        $this->group = $group;
        $this->getNavigation();
    }


    public function loadMedia() {
        $this->mediaList = $this->group->getMedia('files');
    }
    
    public function loadFolders() {
        $this->groups = Group::where('group_id', $this->group->id)
        ->orderBy('created_at', 'desc')
        ->get();
      
    }

    public function getNavigation() {
        $this->navigation = $this->getGroupHierarchy($this->group->id);
    }
    
    public function getGroupHierarchy($groupId) {
        $group = Group::find($groupId);
        if ($group) {
            $hierarchy = [];
            $hierarchy[] = ['id' => $group->id, 'name' => $group->name];

              if (!is_null($group->group_id)) {
                 $parentHierarchy = $this->getGroupHierarchy($group->group_id);
                 $hierarchy = array_merge($hierarchy, $parentHierarchy);
             }
             return $hierarchy;
        }   
        return [];
    }
    
    public function openCreateForm() {
        $this->showCreateForm = true;
    }

    public function closeCreateForm() {
        $this->newFolderName = "";
        $this->showCreateForm = false;
    }

    public function createFolder() {
    
        $validated = $this->validate([
            'newFolderName' => 'required|string|max:50'
        ]);

        Group::create([
            'name' => $validated['newFolderName'],
            'group_id' => $this->group->id,
            'category_id' => $this->group->category_id,
        ]);

        $this->newFolderName = "";
        $this->closeCreateForm();
    }

    public function editFolder($groupId) {
        $group = Group::find($groupId);
        if ($group) {
            $this->editMode = true;
            $this->editFolderId = $group->id;
            $this->editFolderName = $group->name;
        }
    }
    
    public function cancelEdit() {
        $this->editMode = false;
        $this->editFolderId = null;
        $this->editFolderName = "";
    }

    public function updateFolder() {
        $validated = $this->validate([
            'editFolderName' => 'required|string|max:50'
        ]);
    
        $group = Group::find($this->editFolderId);
        if ($group) {
            $group->name = $validated['editFolderName'];
            $group->save();
            $this->editMode = false;
            $this->editFolderId = null;
            $this->editFolderName = "";
        }
    }    

    public function render()
    {
        $this->loadFolders();
        $this->loadMedia();
        return view('livewire.group-selector')
        ->extends('layouts.app')
        ->section('content');
    }
}