<?php

namespace Tests\Feature;

use App\Mail\DestroyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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

    public function test_user_can_visit_change_password_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('users.changePassword', $user->id))
            ->assertSuccessful();
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->put(route('users.changePassword', $user->id), [
            'current_password' => 'Password101',
            'new_password' => 'NewPassword101',
            'new_password_confirmation' => 'NewPassword101',
        ])
            ->assertStatus(302)
            ->assertSessionHas('status', '密碼修改成功！');
    }

    public function test_user_can_visit_delete_account_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('users.delete', $user->id))
            ->assertSuccessful();
    }

    public function test_send_destroy_user_email_queue()
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('users.sendDestroyEmail', $user->id))
            ->assertStatus(302);

        Mail::assertQueued(DestroyUser::class);
    }

    public function test_user_can_delete_own_account()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->actingAs($user);

        $destroyUserLink = URL::temporarySignedRoute(
            'users.destroy',
            now()->addMinutes(5),
            ['user' => $user->id]
        );

        $this->get($destroyUserLink)->assertStatus(302);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_can_not_delete_own_account_if_url_is_invalid()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->actingAs($user);

        $destroyUserLink = URL::temporarySignedRoute(
            'users.destroy',
            now()->addMinutes(5),
            ['user' => $user->id]
        );

        // 讓時間經過 6 分鐘，使連結失效
        $this->travel(6)->minutes();

        $this->get($destroyUserLink)->assertStatus(401);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
