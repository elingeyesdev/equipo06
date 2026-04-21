<?php

namespace Tests\Feature\Ui;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_ve_enlaces_de_administracion_en_la_barra(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get(route('productores.index'))
            ->assertOk()
            ->assertSee('Envíos', false)
            ->assertSee('Responsables', false)
            ->assertSee('Ubicaciones', false)
            ->assertSee('Administrador', false);
    }

    public function test_productor_no_ve_enlaces_de_administracion_en_la_barra(): void
    {
        $user = User::factory()->create(['rol' => 'productor']);

        $this->actingAs($user)
            ->get(route('productor.dashboard'))
            ->assertOk()
            ->assertSee('Mi panel', false)
            ->assertDontSee('>Envíos</a>', false)
            ->assertDontSee('>Responsables</a>', false)
            ->assertDontSee('>Ubicaciones</a>', false);
    }

    public function test_productor_ve_enlaces_de_produccion_en_la_barra(): void
    {
        $user = User::factory()->create(['rol' => 'productor']);

        $this->actingAs($user)
            ->get(route('productores.index'))
            ->assertOk()
            ->assertSee('Productores', false)
            ->assertSee('Lotes', false);
    }
}
