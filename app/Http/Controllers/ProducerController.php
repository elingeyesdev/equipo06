<?php

namespace App\Http\Controllers;

use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProducerController extends Controller
{
    public function index()
    {
        $producers = Producer::query()
            ->latest()
            ->paginate(10);

        $total = Producer::query()->count();
        $activos = Producer::query()->where('is_active', true)->count();

        return view('productores.index', compact('producers', 'total', 'activos'));
    }

    public function create()
    {
        $producer = new Producer;
        $tiposVia = Producer::tiposVia();

        return view('productores.create', compact('producer', 'tiposVia'));
    }

    public function store(Request $request)
    {
        $this->mergeOptionalStrings($request);
        $this->normalizePhoneForRequest($request);

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

    public function show(Producer $producer)
    {
        return view('productores.show', compact('producer'));
    }

    public function edit(Producer $producer)
    {
        $tiposVia = Producer::tiposVia();

        return view('productores.edit', compact('producer', 'tiposVia'));
    }

    public function update(Request $request, Producer $producer)
    {
        $this->mergeOptionalStrings($request);
        $this->normalizePhoneForRequest($request);

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

    public function destroy(Producer $producer)
    {
        $producer->delete();

        return redirect()
            ->route('productores.index')
            ->with('status', 'Productor eliminado correctamente.');
    }

    private function mergeOptionalStrings(Request $request): void
    {
        $request->merge([
            'document_number' => $request->filled('document_number') ? trim($request->input('document_number')) : null,
            'email' => $request->filled('email') ? trim($request->input('email')) : null,
            'address_detail' => $request->filled('address_detail') ? trim($request->input('address_detail')) : null,
            'address_type' => $request->filled('address_type') ? trim($request->input('address_type')) : null,
        ]);
    }

    /**
     * Normaliza teléfono a +591 seguido de 8 dígitos (Bolivia).
     */
    private function normalizePhoneForRequest(Request $request): void
    {
        if (! $request->filled('phone')) {
            $request->merge(['phone' => null]);

            return;
        }

        $digits = preg_replace('/\D/', '', (string) $request->input('phone')) ?? '';
        if (str_starts_with($digits, '591') && strlen($digits) >= 11) {
            $digits = substr($digits, 3);
        }
        if (strlen($digits) === 8) {
            $request->merge(['phone' => '+591'.$digits]);
        }
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

        $tiposVia = array_keys(Producer::tiposVia());

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
                'max:20',
                'regex:/^\d{5,10}$/',
                $uniqueDoc,
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^\+591\d{8}$/',
            ],
            'email' => ['nullable', 'email:rfc', 'max:150'],
            'address_type' => ['nullable', 'required_with:address_detail', 'string', Rule::in($tiposVia)],
            'address_detail' => [
                'nullable',
                'required_with:address_type',
                'string',
                'min:4',
                'max:200',
                'regex:/^[\p{L}0-9\s\.\#\-\/]+$/u',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null || $value === '') {
                        return;
                    }
                    if (! preg_match('/\d/', (string) $value)) {
                        $fail('En la dirección debe incluirse al menos un número (ej. número de puerta o edificio).');
                    }
                },
            ],
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
            'document_number.regex' => 'El carnet de identidad debe contener solo dígitos (5 a 10 caracteres).',
            'phone.regex' => 'El teléfono debe tener el formato +591 seguido de 8 dígitos (ej. +59176045341).',
            'address_detail.regex' => 'Use letras, números, espacios, #, / o guiones (ej. Banzer #123).',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function producerAttributes(): array
    {
        return [
            'full_name' => 'nombre completo',
            'document_number' => 'carnet de identidad',
            'phone' => 'teléfono',
            'email' => 'correo',
            'address_type' => 'tipo de vía',
            'address_detail' => 'dirección',
            'is_active' => 'estado activo',
        ];
    }
}
