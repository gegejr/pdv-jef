<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Produto;

class Search extends Component
{
    public $searchTerm = '';
  //  public $selectedCategories = [];
  //  public $selectedBrands = [];

    protected $queryString = [
        'searchTerm' => ['except' => ''],
    //    'selectedCategories' => ['except' => []],
    //    'selectedBrands' => ['except' => []]
    ];

    public function mount()
    {
        $this->searchTerm = request()->query('searchTerm', $this->searchTerm);
       // $this->selectedCategories = request()->query('selectedCategories', $this->selectedCategories);
       // $this->selectedBrands = request()->query('selectedBrands', $this->selectedBrands);
    }

    public function updatedSearchTerm()
    {
        $this->dispatch('updateQueryString', 'searchTerm', $this->searchTerm)->self();
    }

   /*public function updatedSelectedCategories()
    {
        $this->dispatch('updateQueryString', 'selectedCategories', $this->selectedCategories)->self();
    }

    public function updatedSelectedBrands()
    {
        $this->dispatch('updateQueryString', 'selectedBrands', $this->selectedBrands)->self();
    }

    public function clearCategories()
    {
        $this->selectedCategories = [];
        $this->dispatch('updateQueryString', 'selectedCategories', $this->selectedCategories)->self();
    }

    public function clearBrands()
    {
        $this->selectedBrands = [];
        $this->dispatch('updateQueryString', 'selectedBrands', $this->selectedBrands)->self();
    }
    */
    public function clearFilters()
    {
        $this->searchTerm = '';
        //$this->clearCategories();
        //$this->clearBrands();
    }

    public function render()
    {

        $produtos = Produto::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('nome', 'like', '%' . $this->searchTerm . '%');
            })
            ->get();

        return view('livewire.produto-lista', [

            'produtos' => $produtos,
        ]);
    }
}