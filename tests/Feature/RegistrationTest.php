<?php

namespace Tests\Feature;

use App\Http\Livewire\Layouts\Header;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        Setting::query()->forceCreate([
            'name' => '開放註冊',
            'key' => 'allow_register',
            'value' => 'true',
        ]);

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_guest_can_register()
    {
        Setting::query()->forceCreate([
            'name' => '開放註冊',
            'key' => 'allow_register',
            'value' => 'true',
        ]);

        $response = $this->post('/register', [
            'name' => 'Test_User',
            'email' => 'test@example.com',
            'password' => 'Password101',
            'password_confirmation' => 'Password101',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('verify-email');
    }

    public function test_guest_can_not_visit_register_page_when_register_is_not_allowed()
    {
        Setting::query()->forceCreate([
            'name' => '開放註冊',
            'key' => 'allow_register',
            'value' => 'false',
        ]);

        $this->get(route('register'))->assertStatus(503);
    }

    public function test_guest_can_not_see_register_button()
    {
        Setting::query()->forceCreate([
            'name' => '開放註冊',
            'key' => 'allow_register',
            'value' => 'false',
        ]);

        Livewire::test(Header::class)
            ->assertDontSeeText('註冊');
    }

    public function test_guest_can_not_register_when_register_is_not_allowed()
    {
        Setting::query()->forceCreate([
            'name' => '開放註冊',
            'key' => 'allow_register',
            'value' => 'false',
        ]);

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'John@email.com',
            'password' => 'Password01!',
            'password_confirmation' => 'Password01!',
        ])->assertStatus(503);
    }
}
