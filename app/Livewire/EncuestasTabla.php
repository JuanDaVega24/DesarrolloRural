<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Encuesta;

class EncuestasTabla extends Component
{
    use WithPagination;

    // Filtros
    public $fecha_encuesta = '';
    public $nombre_identidad = '';
    public $primer_apellido = '';
    public $numero_documento = '';
    public $vereda = '';

    // Ordenamiento
    public $sort = 'id';
    public $direction = 'desc';

    protected $paginationTheme = 'bootstrap';

    // Reset pagination when filters cambian
    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'fecha_encuesta', 
            'nombre_identidad', 
            'primer_apellido', 
            'numero_documento', 
            'vereda'
        ])) {
            $this->resetPage();
        }
    }

    public function ordenar($campo)
    {
        if ($this->sort === $campo) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $campo;
            $this->direction = 'asc';
        }
    }

    private function getOrderByColumn()
    {
        // Si se ordena por la vereda, Laravel debe ordenar por la relación
        if ($this->sort === 'vereda') {
            return null; // Lo manejamos manualmente en el query
        }

        return $this->sort;
    }

    public function limpiarFiltros()
    {
        $this->reset([
            'fecha_encuesta',
            'nombre_identidad',
            'primer_apellido',
            'numero_documento',
            'vereda'
        ]);
        $this->resetPage();
    }

    public function render()
    {
        $encuestas = Encuesta::with(['vereda', 'corregimiento'])

            // === FILTROS ===
            ->when($this->fecha_encuesta, fn($q) =>
                $q->whereDate('fecha_encuesta', $this->fecha_encuesta)
            )
            ->when($this->nombre_identidad, fn($q) =>
                $q->where('nombre_identidad', 'like', "%{$this->nombre_identidad}%")
            )
            ->when($this->primer_apellido, fn($q) =>
                $q->where('primer_apellido', 'like', "%{$this->primer_apellido}%")
            )
            ->when($this->numero_documento, fn($q) =>
                $q->where('numero_documento', 'like', "%{$this->numero_documento}%")
            )
            ->when($this->vereda, fn($q) =>
                $q->whereHas('vereda', function ($v) {
                    $v->where('nombre', 'like', "%{$this->vereda}%");
                })
            )

            // === ORDENAMIENTO ===
            ->when($this->sort === 'vereda', function ($query) {
                // Ordena por nombre de la vereda mediante la relación
                $query->select('encuestas.*')
                      ->join('veredas', 'encuestas.vereda_id', '=', 'veredas.id')
                      ->orderBy('veredas.nombre', $this->direction);
            })

            ->when($this->sort !== 'vereda', function ($query) {
                $query->orderBy($this->sort, $this->direction);
            })

            ->paginate(10);

        $veredas = \App\Models\Vereda::orderBy('nombre')->get();
        $totalRegistros = Encuesta::count();

        return view('livewire.encuestas-tabla', [
            'encuestas' => $encuestas,
            'veredas' => $veredas,
            'totalRegistros' => $totalRegistros
        ]);
    }
}
