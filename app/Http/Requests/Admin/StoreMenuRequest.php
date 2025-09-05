<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route as RouteFacade;

class StoreMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ajuste conforme sua política. Mantive admin como no grupo de rotas.
        return $this->user()?->hasRole('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            // Qual o tipo de destino do item
            'destination' => ['required', Rule::in(['route', 'url', 'group'])],

            // Básico
            'label'      => ['required', 'string', 'max:191'],
            'key'        => ['nullable', 'string', 'max:191', 'unique:menu_overrides,key'],
            'parent_key' => ['nullable', 'string', 'max:191', 'different:key'],
            'order'      => ['nullable', 'integer', 'min:0'],

            // Visuais/opções
            'icon'    => ['nullable', 'string', 'max:191'],
            'new_tab' => ['nullable', 'boolean'],
            'hidden'  => ['nullable', 'boolean'],

            // Destinos
            'route_name' => [
                Rule::requiredIf(fn () => $this->input('destination') === 'route'),
                'nullable', 'string', 'max:191',
                function ($attr, $value, $fail) {
                    if ($this->input('destination') === 'route' && $value && !RouteFacade::has($value)) {
                        $fail("A rota '{$value}' não existe.");
                    }
                },
            ],
            'custom_url' => [
                Rule::requiredIf(fn () => $this->input('destination') === 'url'),
                'nullable', 'url', 'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'label.required'         => 'Informe um rótulo.',
            'destination.required'   => 'Escolha o tipo de destino.',
            'route_name.required'    => 'Escolha a rota.',
            'custom_url.required'    => 'Informe a URL.',
            'key.unique'             => 'Já existe um item com essa key.',
            'parent_key.different'   => 'O pai não pode ser o próprio item.',
        ];
    }

    /**
     * Normalizações e preenchimentos automáticos antes da validação.
     */
    protected function prepareForValidation(): void
    {
        // Normaliza checkboxes
        $data = [
            'new_tab' => (bool) $this->boolean('new_tab'),
            'hidden'  => (bool) $this->boolean('hidden'),
        ];

        // Limpa campos conforme o destino escolhido
        $dest = $this->input('destination');
        if ($dest === 'route') {
            $data['custom_url'] = null;
        } elseif ($dest === 'url') {
            $data['route_name'] = null;
        } else { // group (sem destino)
            $data['route_name'] = null;
            $data['custom_url'] = null;
        }

        // Auto-gerar key se não vier:
        // 1) usa o nome da rota quando houver
        // 2) senão, slug do label com separador '.'
        if (!$this->filled('key')) {
            if ($this->filled('route_name')) {
                $data['key'] = $this->input('route_name');           // ex.: "users.index"
            } elseif ($this->filled('label')) {
                $data['key'] = Str::slug($this->input('label'), '.'); // ex.: "Relatórios Usuários" => "relatorios.usuarios"
            }
        }

        $this->merge($data);
    }
}
