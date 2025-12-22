@props(['steps', 'current' => 1, 'progress' => 0])

{{-- Incluye Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root {
        --verde: #4A7C2F;
        --verde-claro: #E8F5E0;
        --azul: #3366CC;
        --azul-claro: #E3ECFA;
        --beige: #F8F6F3;
    }

    .steps-wrapper {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 1.25rem;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin: 2rem auto;
        max-width: 1200px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .progress-section {
        margin-bottom: 3rem;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .progress-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: #1A1A1A;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .progress-percentage {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--verde);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .progress-percentage i {
        font-size: 1rem;
    }

    .custom-progress {
        height: 12px;
        background: linear-gradient(90deg, #e9ecef 0%, #dee2e6 100%);
        border-radius: 999px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .custom-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--verde) 0%, #5a9c3f 100%);
        border-radius: 999px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .custom-progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(
            90deg,
            transparent 0%,
            rgba(255, 255, 255, 0.3) 50%,
            transparent 100%
        );
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .steps-timeline {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .step-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .step-icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 1rem;
        cursor: pointer;
    }

    .step-icon-wrapper.active {
        background: linear-gradient(135deg, var(--verde) 0%, #5a9c3f 100%);
        box-shadow: 0 8px 24px rgba(74, 124, 47, 0.35);
        transform: scale(1.1);
        animation: pulse 2s infinite;
    }

    .step-icon-wrapper.completed {
        background: linear-gradient(135deg, var(--verde) 0%, #3d6625 100%);
        box-shadow: 0 4px 12px rgba(74, 124, 47, 0.3);
    }

    .step-icon-wrapper.pending {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 8px 24px rgba(74, 124, 47, 0.35);
        }
        50% {
            box-shadow: 0 8px 32px rgba(74, 124, 47, 0.5);
        }
    }

    .step-icon-wrapper::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: inherit;
        opacity: 0.3;
        transform: scale(0);
        transition: transform 0.4s ease;
    }

    .step-icon-wrapper.active::before {
        transform: scale(1.3);
        animation: ripple 1.5s infinite;
    }

    @keyframes ripple {
        0% {
            transform: scale(1);
            opacity: 0.3;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }

    .step-icon {
        font-size: 1.75rem;
        transition: all 0.3s ease;
    }

    .step-icon-wrapper.active .step-icon,
    .step-icon-wrapper.completed .step-icon {
        color: #ffffff;
    }

    .step-icon-wrapper.pending .step-icon {
        color: #6c757d;
    }

    .step-icon-wrapper:hover {
        transform: scale(1.15);
    }

    .step-check {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
        animation: checkBounce 0.5s ease;
    }

    @keyframes checkBounce {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.2);
        }
    }

    .step-check i {
        color: white;
        font-size: 0.875rem;
        font-weight: bold;
    }

    .step-label {
        text-align: center;
        font-size: 0.9375rem;
        font-weight: 600;
        transition: all 0.3s ease;
        max-width: 100px;
    }

    .step-label.active {
        color: var(--verde);
        font-weight: 700;
        font-size: 1rem;
    }

    .step-label.completed {
        color: #495057;
    }

    .step-label.pending {
        color: #adb5bd;
    }

    .step-number {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .step-number.active {
        color: var(--verde);
        font-weight: 700;
    }

    /* Línea conectora */
    .step-connector {
        position: absolute;
        top: 35px;
        left: 0;
        right: 0;
        height: 4px;
        z-index: 1;
        display: flex;
    }

    .connector-segment {
        flex: 1;
        height: 100%;
        background: #dee2e6;
        position: relative;
        margin: 0 35px;
        border-radius: 999px;
    }

    .connector-segment.completed {
        background: linear-gradient(90deg, var(--verde) 0%, #5a9c3f 100%);
        animation: fillLine 0.6s ease;
    }

    @keyframes fillLine {
        from {
            transform: scaleX(0);
            transform-origin: left;
        }
        to {
            transform: scaleX(1);
        }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .steps-wrapper {
            padding: 2rem 1.5rem;
        }

        .step-icon-wrapper {
            width: 60px;
            height: 60px;
        }

        .step-icon {
            font-size: 1.5rem;
        }

        .step-label {
            font-size: 0.8125rem;
            max-width: 80px;
        }
    }

    @media (max-width: 768px) {
        .steps-wrapper {
            padding: 1.5rem 1rem;
        }

        .steps-timeline {
            flex-direction: column;
            align-items: stretch;
        }

        .step-item {
            flex-direction: row;
            justify-content: flex-start;
            margin-bottom: 1.5rem;
            padding-left: 1rem;
        }

        .step-item:last-child {
            margin-bottom: 0;
        }

        .step-icon-wrapper {
            margin-bottom: 0;
            margin-right: 1rem;
            width: 56px;
            height: 56px;
        }

        .step-label {
            text-align: left;
            max-width: none;
            flex: 1;
        }

        .step-connector {
            top: 0;
            left: 28px;
            right: auto;
            width: 4px;
            height: 100%;
            flex-direction: column;
        }

        .connector-segment {
            margin: 28px 0;
        }

        .progress-percentage {
            font-size: 1.125rem;
        }
    }
</style>

<div class="steps-wrapper">
    
    {{-- Barra de progreso mejorada --}}
    <div class="progress-section">
        <div class="progress-header">
            <span class="progress-title">
                <i class="bi bi-graph-up-arrow"></i> Progreso de la Encuesta
            </span>
            <span class="progress-percentage">
                <i class="bi bi-percent"></i>
                {{ $progress }}%
            </span>
        </div>
        <div class="custom-progress">
            <div class="custom-progress-bar" style="width: {{ $progress }}%;"></div>
        </div>
    </div>

    {{-- Timeline con iconos mejorados --}}
    <div class="steps-timeline">
        
        {{-- Línea conectora --}}
        <div class="step-connector">
            @for($i = 0; $i < count($steps) - 1; $i++)
                <div class="connector-segment {{ ($i + 1) < $current ? 'completed' : '' }}"></div>
            @endfor
        </div>

        @foreach($steps as $i => $step)
            @php
                $stepNumber = $i + 1;
                $isActive = $stepNumber === $current;
                $isCompleted = $stepNumber < $current;
                $isPending = $stepNumber > $current;
                
                // Iconos por paso
                $icons = [
                    1 => 'person-fill',
                    2 => 'house-door-fill',
                    3 => 'envelope-paper-fill',
                    4 => 'gear-fill',
                    5 => 'tools',
                    6 => 'piggy-bank-fill',
                    7 => 'check-square',
                ];
                $icon = $icons[$stepNumber] ?? 'circle-fill';
                
                $status = $isActive ? 'active' : ($isCompleted ? 'completed' : 'pending');
            @endphp

            <div class="step-item">
                <div class="step-icon-wrapper {{ $status }}">
                    <i class="bi bi-{{ $icon }} step-icon"></i>
                    @if($isCompleted)
                        <div class="step-check">
                            <i class="bi bi-check-lg"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="step-label {{ $status }}">
                        {{ $step }}
                    </div>
                    <div class="step-number {{ $status }}">
                        Paso {{ $stepNumber }}/{{ count($steps) }}
                    </div>
                </div>
            </div>
        @endforeach

    </div>

</div>