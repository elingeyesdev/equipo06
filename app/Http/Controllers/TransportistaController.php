<?php

namespace App\Http\Controllers;

use App\Models\Transportista;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransportistaController extends Controller
{
    public function index()
    {
        $transportistas = Transportista::query()
            ->latest()
            ->paginate(10);

        $total = Transportista::query()->count();
        $disponibles = Transportista::query()->where('estado', 'activo')->count();

        return view('transportistas.index', compact('transportistas', 'total', 'disponibles'));
    }

    public function create()
    {
        $transportista = new Transportista;
        $estados = Transportista::estadosDisponibles();

        return view('transportistas.create', compact('transportista', 'estados'));
    }

    public function store(Request $request)
    {
        $this->normalizeInputs($request);

        $validated = $request->validate(
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );

        Transportista::create($validated);

        return redirect()
            ->route('transportistas.index')
            ->with('status', 'Responsable de transporte registrado correctamente.');
    }

    public function show(Transportista $transportista)
    {
        return view('transportistas.show', compact('transportista'));
    }

    public function edit(Transportista $transportista)
    {
        $estados = Transportista::estadosDisponibles();

        return view('transportistas.edit', compact('transportista', 'estados'));
    }

    public function update(Request $request, Transportista $transportista)
    {
        $this->normalizeInputs($request);

        $validated = $request->validate(
            $this->rules($transportista->id),
            $this->messages(),
            $this->attributes()
        );

        $transportista->update($validated);

        return redirect()
            ->route('transportistas.index')
            ->with('status', 'Responsable de transporte actualizado correctamente.');
    }

    public function destroy(Transportista $transportista)
    {
        $transportista->delete();

        return redirect()
            ->route('transportistas.index')
            ->with('status', 'Responsable de transporte eliminado correctamente.');
    }

    private function normalizeInputs(Request $request): void
    {
        $nombre = $request->filled('nombre') ? trim((string) $request->input('nombre')) : null;
        $apellido = $request->filled('apellido') ? trim((string) $request->input('apellido')) : null;
        $carnet = $request->filled('carnet_identidad') ? strtoupper(trim((string) $request->input('carnet_identidad'))) : null;
        $licencia = $request->filled('licencia') ? strtoupper(trim((string) $request->input('licencia'))) : null;
        $tipoLicencia = $request->filled('tipo_licencia') ? strtoupper(trim((string) $request->input('tipo_licencia'))) : null;

        $telefono = null;
        if ($request->filled('telefono')) {
            $digits = preg_replace('/\D/', '', (string) $request->input('telefono')) ?? '';
            if (str_starts_with($digits, '591') && strlen($digits) >= 11) {
                $digits = substr($digits, 3);
            }
            $telefono = strlen($digits) === 8 ? '+591'.$digits : trim((string) $request->input('telefono'));
        }

        $request->merge([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'carnet_identidad' => $carnet,
            'telefono' => $telefono,
            'licencia' => $licencia,
            'tipo_licencia' => $tipoLicencia,
            'estado' => $request->filled('estado') ? trim((string) $request->input('estado')) : 'activo',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?int $ignoreId = null): array
    {
        $uniqueCarnet = Rule::unique('transportistas', 'carnet_identidad');
        if ($ignoreId !== null) {
            $uniqueCarnet = $uniqueCarnet->ignore($ignoreId);
        }

        $estados = array_keys(Transportista::estadosDisponibles());

        return [
            'nombre' => ['required', 'string', 'min:2', 'max:160', 'regex:/^[\p{L}\s\.\'-]+$/u'],
            'apellido' => ['required', 'string', 'min:2', 'max:160', 'regex:/^[\p{L}\s\.\'-]+$/u'],
            'carnet_identidad' => ['required', 'string', 'min:5', 'max:20', 'regex:/^[A-Z0-9\-]+$/', $uniqueCarnet],
            'telefono' => ['required', 'string', 'regex:/^\+591\d{8}$/'],
            'licencia' => ['required', 'string', 'min:4', 'max:64'],
            'tipo_licencia' => ['required', 'string', 'min:1', 'max:32'],
            'fecha_vencimiento_licencia' => ['nullable', 'date'],
            'estado' => ['required', 'string', Rule::in($estados)],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'nombre.regex' => 'El nombre solo puede incluir letras, espacios, puntos, apóstrofes o guiones.',
            'apellido.regex' => 'El apellido solo puede incluir letras, espacios, puntos, apóstrofes o guiones.',
            'carnet_identidad.regex' => 'El carnet de identidad solo puede incluir letras, números y guiones.',
            'telefono.regex' => 'El teléfono debe tener el formato +591 seguido de 8 dígitos (ej. +59176045341).',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'carnet_identidad' => 'carnet de identidad',
            'telefono' => 'teléfono',
            'licencia' => 'licencia',
            'tipo_licencia' => 'tipo de licencia',
            'fecha_vencimiento_licencia' => 'fecha de vencimiento de licencia',
            'estado' => 'estado',
        ];
    }
}
