<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingProduct = null;
    
    public $nombre = '';
    public $descripcion = '';
    public $categoria = '';
    public $costo_puntos = '';
    public $stock = '';
    public $activo = true;
    public $integra_jira = false;
    public $envia_email = false;
    public $terminos_condiciones = '';
    public $imagen_url = '';
    
    public $search = '';
    public $categoryFilter = '';
    public $activeFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'activeFilter' => ['except' => ''],
    ];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'categoria' => 'required|string|max:255',
        'costo_puntos' => 'required|integer|min:1|max:100000',
        'stock' => 'required|integer|min:-1',
        'activo' => 'boolean',
        'integra_jira' => 'boolean',
        'envia_email' => 'boolean',
        'terminos_condiciones' => 'nullable|string',
        'imagen_url' => 'nullable|url|max:1000',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingActiveFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editingProduct = null;
        $this->showModal = true;
    }

    public function openEditModal($productId)
    {
        $product = Product::findOrFail($productId);
        $this->editingProduct = $product;
        
        $this->nombre = $product->nombre;
        $this->descripcion = $product->descripcion;
        $this->categoria = $product->categoria;
        $this->costo_puntos = $product->costo_puntos;
        $this->stock = $product->stock;
        $this->activo = $product->activo;
        $this->integra_jira = $product->integra_jira;
        $this->envia_email = $product->envia_email;
        $this->terminos_condiciones = $product->terminos_condiciones;
        $this->imagen_url = $product->imagen_url;
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingProduct = null;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingProduct) {
                $this->editingProduct->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'categoria' => $this->categoria,
                    'costo_puntos' => (int) $this->costo_puntos,
                    'stock' => (int) $this->stock,
                    'activo' => $this->activo,
                    'integra_jira' => $this->integra_jira,
                    'envia_email' => $this->envia_email,
                    'terminos_condiciones' => $this->terminos_condiciones,
                    'imagen_url' => $this->imagen_url,
                ]);
                
                session()->flash('success', 'Producto actualizado exitosamente.');
            } else {
                Product::create([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'categoria' => $this->categoria,
                    'costo_puntos' => (int) $this->costo_puntos,
                    'stock' => (int) $this->stock,
                    'activo' => $this->activo,
                    'integra_jira' => $this->integra_jira,
                    'envia_email' => $this->envia_email,
                    'terminos_condiciones' => $this->terminos_condiciones,
                    'imagen_url' => $this->imagen_url,
                ]);
                
                session()->flash('success', 'Producto creado exitosamente.');
            }

            $this->closeModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el producto: ' . $e->getMessage());
        }
    }

    public function toggleActive($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['activo' => !$product->activo]);
        
        $status = $product->activo ? 'activado' : 'desactivado';
        session()->flash('success', "Producto {$status} exitosamente.");
    }

    public function deleteProduct($productId)
    {
        $product = Product::findOrFail($productId);
        
        if ($product->orders()->exists()) {
            session()->flash('error', 'No se puede eliminar un producto que tiene órdenes asociadas.');
            return;
        }
        
        $product->favorites()->delete();
        $product->delete();
        
        session()->flash('success', 'Producto eliminado exitosamente.');
    }

    protected function resetForm()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->categoria = '';
        $this->costo_puntos = '';
        $this->stock = '';
        $this->activo = true;
        $this->integra_jira = false;
        $this->envia_email = false;
        $this->terminos_condiciones = '';
        $this->imagen_url = '';
        $this->resetValidation();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                  ->orWhere('categoria', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('categoria', $this->categoryFilter);
        }

        if ($this->activeFilter !== '') {
            $query->where('activo', $this->activeFilter === '1');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $categories = Product::whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria')
            ->sort();

        return view('livewire.admin.product-management', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
