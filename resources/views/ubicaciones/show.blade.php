@extends('layouts.app')

@section('title', 'Ubicación '.$ubicacion->nombre_ubicacion)

@php
    $hasCoords = $ubicacion->latitud !== null && $ubicacion->longitud !== null;
    $latNum = $hasCoords ? (float) $ubicacion->latitud : null;
    $lngNum = $hasCoords ? (float) $ubicacion->longitud : null;
@endphp

@push('styles')
    @if ($hasCoords)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    @endif
    <style>
        .btn-ubicacion-main {
            background-color: #3d8b9e;
            border-color: #3d8b9e;
            color: #fff;
            font-weight: 600;
            border-radius: 0.5rem;
            box-shadow: 0 2px 6px rgba(61, 139, 158, 0.35);
        }
        .btn-ubicacion-main:hover { background-color: #327183; border-color: #327183; color: #fff; }
        .ubic-hero {
            border-radius: 0.85rem;
            border: none;
            background: linear-gradient(145deg, #ffffff 0%, #f4faf7 45%, #eef6f3 100%);
            box-shadow: 0 4px 18px rgba(26, 92, 56, 0.08), 0 0 0 1px rgba(26, 92, 56, 0.06);
            position: relative;
            overflow: hidden;
        }
        .ubic-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; bottom: 0;
            width: 5px;
            background: linear-gradient(180deg, #2d8a6e, #3d8b9e);
            border-radius: 0.85rem 0 0 0.85rem;
        }
        .ubic-stat-card {
            border-radius: 0.75rem;
            border: 1px solid rgba(0,0,0,0.06);
            background: #fff;
            transition: transform 0.15s ease;
        }
        .ubic-stat-card:hover { transform: translateY(-2px); }
        .ubic-map-wrap {
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(26, 92, 56, 0.12);
            border: 1px solid rgba(0,0,0,0.08);
            min-height: 320px;
            background: linear-gradient(135deg, #f8fffb 0%, #eef6f3 100%);
        }
        #map-ubicacion-detalle {
            height: 340px;
            width: 100%;
            z-index: 1;
        }
        .ubic-map-empty {
            min-height: 340px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }
        .ubic-geo-card {
            border-radius: 0.75rem;
            border: 1px solid rgba(0,0,0,0.06);
            background: #fff;
        }
        .mono-coord {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.95rem;
        }
        .ubic-section-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6c757d;
        }
        .leaflet-container { font-family: inherit; }
    </style>
@endpush

@section('content')
    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('status') }}</div>
    @endif

    <div class="mb-4 pb-2 border-bottom border-2 border-opacity-10">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3">
            <div class="flex-grow-1">
                <p class="small text-muted text-uppercase mb-1 fw-semibold">ENT_2.2 Gestión de transporte por ubicación</p>
                <h1 class="h3 mb-2 d-flex align-items-center gap-2 flex-wrap">
                    <span class="fs-2" aria-hidden="true">📍</span>
                    <span class="fw-bold">{{ $ubicacion->nombre_ubicacion }}</span>
                    <span class="badge rounded-pill {{ $ubicacion->badgeTipoClass() }} px-3 py-2 fs-6">{{ $ubicacion->etiquetaTipo() }}</span>
                </h1>
                <p class="text-muted mb-0 small">
                    <span class="font-monospace">ID {{ $ubicacion->id }}</span>
                    <span class="mx-2">·</span>
                    <i class="bi bi-clock-history me-1"></i>Registrada {{ $ubicacion->created_at?->format('d/m/Y H:i') ?? '—' }}
                    @if ($ubicacion->updated_at && ! $ubicacion->updated_at->eq($ubicacion->created_at))
                        <span class="mx-2">·</span>
                        <span>Actualizada {{ $ubicacion->updated_at->format('d/m/Y H:i') }}</span>
                    @endif
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                <a href="{{ route('ubicaciones.edit', $ubicacion) }}" class="btn btn-ubicacion-main d-inline-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i>Editar
                </a>
                <a href="{{ route('ubicaciones.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i>Volver al listado
                </a>
                <form action="{{ route('ubicaciones.destroy', $ubicacion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta ubicación?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-5 g-3 mb-4">
        <div class="col">
            <div class="card ubic-stat-card shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase mb-1"><i class="bi bi-signpost me-1"></i>Tipo</div>
                    <span class="badge rounded-pill {{ $ubicacion->badgeTipoClass() }} px-3 py-2">{{ $ubicacion->etiquetaTipo() }}</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card ubic-stat-card shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase mb-1"><i class="bi bi-pin-map me-1"></i>Dirección</div>
                    <p class="mb-0 fw-medium small">{{ $ubicacion->direccion ?: '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card ubic-stat-card shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase mb-1"><i class="bi bi-compass me-1"></i>Latitud</div>
                    <p class="mb-0 mono-coord text-dark">{{ $ubicacion->latitud !== null ? $ubicacion->latitud : '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card ubic-stat-card shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase mb-1"><i class="bi bi-compass me-1"></i>Longitud</div>
                    <p class="mb-0 mono-coord text-dark">{{ $ubicacion->longitud !== null ? $ubicacion->longitud : '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card ubic-stat-card shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase mb-1"><i class="bi bi-calendar-check me-1"></i>Fecha de registro</div>
                    <p class="mb-0 fw-medium small">{{ $ubicacion->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if ($ubicacion->descripcion)
        <div class="card shadow-sm border-0 mb-4 ubic-hero">
            <div class="card-body p-4 ps-4 ps-md-5">
                <div class="ubic-section-title mb-2"><i class="bi bi-chat-left-text me-1"></i>Descripción</div>
                <p class="mb-0 text-secondary">{{ $ubicacion->descripcion }}</p>
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-map text-primary"></i>
                    <span class="fw-semibold">Mapa</span>
                </div>
                <div class="card-body p-0">
                    @if ($hasCoords)
                        <div class="ubic-map-wrap m-3">
                            <div id="map-ubicacion-detalle"
                                 data-lat="{{ $latNum }}"
                                 data-lng="{{ $lngNum }}"
                                 role="img"
                                 aria-label="Mapa de la ubicación"></div>
                        </div>
                    @else
                        <div class="ubic-map-empty m-3 rounded-3 border border-2 border-dashed bg-white">
                            <i class="bi bi-geo-alt text-muted opacity-50" style="font-size: 2.5rem;"></i>
                            <p class="fw-semibold text-secondary mt-3 mb-1">No hay coordenadas registradas para esta ubicación</p>
                            <p class="small text-muted mb-0">Puede editar la ubicación y definir el punto en el mapa o ingresar latitud y longitud.</p>
                            <a href="{{ route('ubicaciones.edit', $ubicacion) }}" class="btn btn-ubicacion-main btn-sm mt-3">Editar ubicación</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ubic-geo-card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-semibold"><i class="bi bi-globe2 text-primary me-2"></i>Ubicación geográfica</span>
                </div>
                <div class="card-body p-4">
                    <dl class="mb-0">
                        <dt class="small text-muted text-uppercase">Tipo de ubicación</dt>
                        <dd class="mb-3">
                            <span class="badge rounded-pill {{ $ubicacion->badgeTipoClass() }}">{{ $ubicacion->etiquetaTipo() }}</span>
                        </dd>
                        <dt class="small text-muted text-uppercase">Dirección</dt>
                        <dd class="mb-3 small">{{ $ubicacion->direccion ?: '—' }}</dd>
                        <dt class="small text-muted text-uppercase">Latitud</dt>
                        <dd class="mb-3 mono-coord">{{ $ubicacion->latitud !== null ? $ubicacion->latitud : '—' }}</dd>
                        <dt class="small text-muted text-uppercase">Longitud</dt>
                        <dd class="mb-0 mono-coord">{{ $ubicacion->longitud !== null ? $ubicacion->longitud : '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    @if ($ubicacion->envio || $ubicacion->ruta)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <span class="fw-semibold"><i class="bi bi-link-45deg text-primary me-2"></i>Vínculos</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @if ($ubicacion->envio)
                        <div class="col-md-6">
                            <div class="small text-muted text-uppercase mb-1">Envío</div>
                            <a href="{{ route('envios.show', $ubicacion->envio) }}" class="fw-semibold text-decoration-none">{{ $ubicacion->envio->codigo }}</a>
                        </div>
                    @endif
                    @if ($ubicacion->ruta)
                        <div class="col-md-6">
                            <div class="small text-muted text-uppercase mb-1">Ruta</div>
                            <span class="fw-medium">#{{ $ubicacion->ruta->id }}{{ $ubicacion->ruta->nombre ? ' · '.$ubicacion->ruta->nombre : '' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@if ($hasCoords)
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const el = document.getElementById('map-ubicacion-detalle');
                if (!el || typeof L === 'undefined') {
                    return;
                }
                const lat = parseFloat(el.dataset.lat);
                const lng = parseFloat(el.dataset.lng);
                if (Number.isNaN(lat) || Number.isNaN(lng)) {
                    return;
                }
                delete L.Icon.Default.prototype._getIconUrl;
                L.Icon.Default.mergeOptions({
                    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                });
                const map = L.map('map-ubicacion-detalle', { scrollWheelZoom: true }).setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);
                L.marker([lat, lng]).addTo(map);
                requestAnimationFrame(function () {
                    map.invalidateSize();
                });
            });
        </script>
    @endpush
@endif
