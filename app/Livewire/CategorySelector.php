<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategorySelector extends Component
{
    public $type;

    public function mount($type) {
        $this->type = $type; 
    }

    
    public function render()
    {   $categories = Category::where('type', $this->type)->get();
     
        
        return view('livewire.category-selector', [
            'categories' =>  $categories,
        ]) 
            ->extends('layouts.app')
            ->section('content');
    }
}