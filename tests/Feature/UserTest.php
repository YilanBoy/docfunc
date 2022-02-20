<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_view_own_profile()
    {
        $user = User::factory()->create();

        $this->get(route('users.index', $user->id))
            ->assertStatus(200);
    }

    public function test_guest_can_not_visit_edit_page()
    {
        $user = User::factory()->create();

        $this->get(route('users.edit', $user->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_user_can_not_visit_others_edit_page()
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();

        $this->actingAs($user)
            ->get(route('users.edit', $otherUser->id))
            ->assertStatus(403);
    }

    public function test_user_can_edit_own_information()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // legal name
        $this->put(route('users.update', $user->id), [
            'name' => 'New_legal_Name',
            'introduction' => $this->faker->realText(119),
        ])
            ->assertStatus(302)
            ->assertRedirect(route('users.index', ['user' => $user->id]));

        // illegal name
        $this->put(route('users.update', $user->id), [
            'name' => 'New illegal Name',
            'introduction' => $this->faker->realText(119),
        ])
            ->assertSessionHasErrors('name');
    }
}
