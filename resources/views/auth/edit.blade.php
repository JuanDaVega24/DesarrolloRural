@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary-govco">Editar Proyecto Productivo</h2>
        <a href="{{ url()->previous() }}" class="btn btn-primary-govco">Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('proyectos.update', $proyecto) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre del Proyecto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ano" class="form-label">Año</label>
                        <input type="number" class="form-control" id="ano" name="ano" value="{{ old('ano', $proyecto->ano) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="origen" class="form-label fw-bold">Método de Creación / Origen</label>
                    <select class="form-select" id="origen" name="origen">
                        <option value="excel" @if(old('origen', $proyecto->origen) == 'excel' || is_null($proyecto->origen)) selected @endif>Excel</option>
                        <option value="manual" @if(old('origen', $proyecto->origen) == 'manual') selected @endif>Manual</option>
                    </select>
                    <div class="form-text">Cambiar el método afecta dónde se gestiona el proyecto. <strong>Manual</strong> aparecerá en Formularios; <strong>Excel</strong> en la carga de archivos.</div>
                </div>

                <button type="submit" class="btn btn-success-govco">
                    <i class="fas fa-save me-2"></i> Actualizar Proyecto
                </button>
            </form>
        </div>
    </div>
</div>
@endsection