<?php

namespace Tests\Feature\Access;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_productor_recibe_403_en_ruta_solo_admin(): void
    {
        $user = User::factory()->create(['rol' => 'productor']);

        $this->actingAs($user)
            ->get('/transportistas')
            ->assertForbidden();
    }

    public function test_admin_puede_acceder_a_ruta_solo_admin(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get('/transportistas')
            ->assertOk();
    }

    public function test_productor_puede_acceder_a_productores(): void
    {
        $user = User::factory()->create(['rol' => 'productor']);

        $this->actingAs($user)
            ->get('/productores')
            ->assertOk();
    }

    public function test_admin_puede_ver_panel_productor_si_accede_directamente(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->get('/productor')
            ->assertForbidden();
    }
}
