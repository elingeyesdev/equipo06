<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_correcto_permite_ingresar(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('Secret123!'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect('/productores');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_incorrecto_muestra_mensaje_claro(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('Secret123!'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'incorrecta',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email' => 'Credenciales incorrectas']);
        $this->assertGuest();
    }

    public function test_logout_cierra_sesion_correctamente(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
