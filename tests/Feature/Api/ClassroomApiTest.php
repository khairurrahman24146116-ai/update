<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassroomApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_classroom(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->postJson('/api/classrooms', [
                'name' => 'X-1',
                'grade' => 10,
            ])
            ->assertStatus(201)
            ->assertJsonPath('name', 'X-1');
    }

    public function test_non_admin_cannot_create_classroom(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $this->actingAs($guru)
            ->postJson('/api/classrooms', [
                'name' => 'X-2',
                'grade' => 10,
            ])
            ->assertStatus(403);
    }
}
