<x-app-layout>

<div class="max-w-7xl mx-auto py-10 px-6">

    <h1 class="text-2xl font-bold mb-6">Nueva Caracterización</h1>

    <form action="{{ route('encuestas.datos_personales') }}" method="POST">
        @csrf

        {{-- TABS --}}
        <ul class="flex border-b mb-6" id="tabs">
            <li class="-mb-px mr-2">
                <a class="tab active" data-target="#tab-encuesta">Encuesta</a>
            </li>
            <li class="mr-2">
                <a class="tab" data-target="#tab-vivienda">Vivienda</a>
            </li>
            <li class="mr-2">
                <a class="tab" data-target="#tab-descripcion">Descripción</a>
            </li>
            <li class="mr-2">
                <a class="tab" data-target="#tab-produccion">Producción</a>
            </li>
            <li class="mr-2">
                <a class="tab" data-target="#tab-maquinaria">Maquinaria</a>
            </li>
        </ul>

        {{-- 🔵 TAB 1 - ENCUESTA --}}
        <div id="tab-encuesta" class="tab-content block">

            <h2 class="text-xl font-semibold mb-4">Datos Generales</h2>

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="font-semibold">Nombre del encuestador</label>
                    <input type="text" name="nombre_encuestador" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="font-semibold">Unidad de medida del área</label>
                    <select name="unidad_medida" class="w-full border rounded p-2">
                        <option value="HA">HA</option>
                        <option value="MTS">MTS</option>
                    </select>
                </div>

            </div>

        </div>

        {{-- 🔵 TAB 2 - VIVIENDA --}}
        <div id="tab-vivienda" class="tab-content hidden">
            <h2 class="text-xl font-semibold mb-4">Vivienda</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold">Tipo de vivienda</label>
                    <input type="text" name="v_tipo_vivienda" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="font-semibold">Material de Piso</label>
                    <input type="text" name="v_material_piso" class="w-full border p-2 rounded">
                </div>
            </div>
        </div>


        {{-- 🔵 TAB 3 - DESCRIPCIÓN --}}
        <div id="tab-descripcion" class="tab-content hidden">
            <h2 class="text-xl font-semibold mb-4">Descripción</h2>

            <div>
                <label class="font-semibold">Acueducto público</label>
                <input type="number" name="d_acueducto_publico" class="border p-2 rounded w-full">
            </div>
        </div>


        {{-- 🔵 TAB 4 - PRODUCCIÓN --}}
        <div id="tab-produccion" class="tab-content hidden">
            <h2 class="text-xl font-semibold mb-4">Producción</h2>

            <div>
                <label class="font-semibold">Tipo de cultivo</label>
                <input type="text" name="pr_tipo_cultivo" class="border p-2 rounded w-full">
            </div>
        </div>


        {{-- 🔵 TAB 5 - MAQUINARIA --}}
        <div id="tab-maquinaria" class="tab-content hidden">
            <h2 class="text-xl font-semibold mb-4">Maquinaria</h2>

            <div>
                <label class="font-semibold">Tipo</label>
                <input type="text" name="m_tipo" class="border p-2 rounded w-full">
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Guardar Caracterización
            </button>
        </div>

    </form>

</div>

{{-- JAVASCRIPT DE TABS --}}
<script>
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {

            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            contents.forEach(c => c.classList.add('hidden'));
            document.querySelector(tab.dataset.target).classList.remove('hidden');
        });
    });
</script>

<style>
    .tab {
        padding: 10px 14px;
        display: inline-block;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        font-weight: 600;
        color: #555;
    }

    .tab.active {
        border-bottom-color: #3366CC;
        color: #000;
    }

    .hidden {
        display: none;
    }

    .block {
        display: block;
    }
</style>

</x-app-layout>
