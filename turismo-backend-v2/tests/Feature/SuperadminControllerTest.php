<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;

use Database\Seeders\RolesSeeder;


class SuperadminControllerTest extends TestCase
{

    use RefreshDatabase;
    protected $superadmin;
    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar el seeder de roles directamente en el entorno de pruebas
        $this->seed(RolesSeeder::class);

        // Crear usuario de prueba con rol superadmin
        $this->superadmin = User::factory()->create();
        $this->superadmin->assignRole('superadmin');
    }

    /** @test */
    public function puede_crear_usuario_emprendedor()
    {
        $this->actingAs($this->superadmin);

        $response = $this->postJson('/api/superadmin/crear-usuario-emprendedor', [
            'name' => 'Juan Tester',
            'email' => 'juan@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);


        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Usuario emprendedor creado exitosamente',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'juan@test.com']);
    }

    /** @test */
   public function puede_listar_empresas_pendientes()
{
    // Autenticar como superadmin con guard 'sanctum'
    $this->actingAs($this->superadmin, 'sanctum');

    // Crear empresas con estado 'pendiente'
    \App\Models\Company::factory()->count(2)->create([
        'status' => 'pendiente',
    ]);

    // Llamar a la ruta real
    $response = $this->getJson('/api/superadmin/empresas/pendientes');

    // Verificar respuesta exitosa
    $response->assertStatus(200)
             ->assertJsonStructure(['empresas']);
}

    /** @test */
    public function puede_aprobar_empresa()
    {
        $this->actingAs($this->superadmin);

        $empresa = Company::factory()->create(['status' => 'pendiente']);

        $response = $this->putJson("/api/superadmin/aprobar-empresa/{$empresa->id}");

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Empresa aprobada exitosamente.',
            ]);

        $this->assertDatabaseHas('companies', [
            'id' => $empresa->id,
            'status' => 'aprobada',
        ]);
    }

    /** @test */
    public function puede_rechazar_empresa()
    {
        $this->actingAs($this->superadmin);

        $empresa = Company::factory()->create(['status' => 'pendiente']);

        $response = $this->putJson("/api/superadmin/rechazar-empresa/{$empresa->id}");

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Empresa rechazada exitosamente.',
            ]);

        $this->assertDatabaseHas('companies', [
            'id' => $empresa->id,
            'status' => 'rechazada',
        ]);
    }

    /** @test */
    public function puede_listar_todas_las_empresas()
    {
        $this->actingAs($this->superadmin);

        Company::factory()->count(3)->create();

        $response = $this->getJson('/api/superadmin/empresas/lista');

        $response->assertStatus(200)
            ->assertJsonStructure(['empresas']);
    }
}
