<?php

namespace App\Http\Controllers;

use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProducerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $producers = Producer::query()
            ->latest()
            ->paginate(10);

        $total = Producer::query()->count();
        $activos = Producer::query()->where('is_active', true)->count();

        return view('productores.index', compact('producers', 'total', 'activos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $producer = new Producer;

        return view('productores.create', compact('producer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->mergeOptionalStrings($request);

        $validated = $request->validate(
            $this->producerRules(),
            $this->producerMessages(),
            $this->producerAttributes()
        );

        $validated['is_active'] = $request->boolean('is_active');

        Producer::create($validated);

        return redirect()
            ->route('productores.index')
            ->with('status', 'Productor registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producer $producer)
    {
        return view('productores.show', compact('producer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producer $producer)
    {
        return view('productores.edit', compact('producer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producer $producer)
    {
        $this->mergeOptionalStrings($request);

        $validated = $request->validate(
            $this->producerRules($producer->id),
            $this->producerMessages(),
            $this->producerAttributes()
        );

        $validated['is_active'] = $request->boolean('is_active');

        $producer->update($validated);

        return redirect()
            ->route('productores.index')
            ->with('status', 'Productor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producer $producer)
    {
        $producer->delete();

        return redirect()
            ->route('productores.index')
            ->with('status', 'Productor eliminado correctamente.');
    }

    /**
     * Convierte cadenas vacías en null para que no fallen reglas "si viene valor".
     */
    private function mergeOptionalStrings(Request $request): void
    {
        $request->merge([
            'document_number' => $request->filled('document_number') ? trim($request->input('document_number')) : null,
            'phone' => $request->filled('phone') ? trim($request->input('phone')) : null,
            'email' => $request->filled('email') ? trim($request->input('email')) : null,
            'address' => $request->filled('address') ? trim($request->input('address')) : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function producerRules(?int $ignoreProducerId = null): array
    {
        $uniqueDoc = Rule::unique('producers', 'document_number');
        if ($ignoreProducerId !== null) {
            $uniqueDoc = $uniqueDoc->ignore($ignoreProducerId);
        }

        return [
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:150',
                'regex:/^[\p{L}\s\.\'-]+$/u',
            ],
            'document_number' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^(?=.*\d)[A-Za-z0-9\-\.]{4,50}$/',
                $uniqueDoc,
            ],
            'phone' => [
                'nullable',
                'string',
                'max:25',
                'regex:/^[\d\+\-\s\(\)]+$/',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $digits = preg_replace('/\D/', '', (string) $value) ?? '';
                    if (strlen($digits) < 7 || strlen($digits) > 15) {
                        $fail('El teléfono debe contener entre 7 y 15 dígitos.');
                    }
                },
            ],
            'email' => ['nullable', 'email:rfc', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function producerMessages(): array
    {
        return [
            'full_name.regex' => 'El nombre solo puede incluir letras, espacios, puntos, apóstrofes o guiones.',
            'document_number.regex' => 'El documento debe tener al menos 4 caracteres, incluir al menos un número y solo letras, números, guiones o puntos.',
            'phone.regex' => 'El teléfono solo puede incluir dígitos, espacios, +, - o paréntesis.',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function producerAttributes(): array
    {
        return [
            'full_name' => 'nombre completo',
            'document_number' => 'documento',
            'phone' => 'teléfono',
            'email' => 'correo',
            'address' => 'dirección',
            'is_active' => 'estado activo',
        ];
    }
}
