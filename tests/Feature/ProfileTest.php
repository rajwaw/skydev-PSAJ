<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_update_profile_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'testuser1@mandalacare.com',
        ]);

        $response = $this->actingAs($user)->post('/profile/update', [
            'name' => 'Nama Baru User',
        ]);

        $response->assertSessionHas('profile_success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru User',
        ]);

        $user->delete();
    }

    public function test_user_can_update_profile_avatar(): void
    {
        $user = User::factory()->create([
            'name' => 'Avatar Tester',
            'email' => 'testavatar@mandalacare.com',
        ]);

        $file = UploadedFile::fake()->create('custom_avatar.png', 10, 'image/png');

        $response = $this->actingAs($user)->post('/profile/update', [
            'name' => 'Avatar Tester',
            'avatar' => $file,
        ]);

        $response->assertSessionHas('profile_success');
        $user->refresh();

        $this->assertNotNull($user->avatar);
        $this->assertFileExists(public_path($user->avatar));

        // Cleanup
        if (file_exists(public_path($user->avatar))) {
            @unlink(public_path($user->avatar));
        }
        $user->delete();
    }

    public function test_user_can_update_email_and_password_and_login_with_new_credentials(): void
    {
        $user = User::factory()->create([
            'name' => 'Account Tester',
            'email' => 'old_email@mandalacare.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->post('/profile/account', [
            'email' => 'new_email@mandalacare.com',
            'password' => 'newsecretpass123',
            'password_confirmation' => 'newsecretpass123',
        ]);

        $response->assertSessionHas('account_success');

        $user->refresh();
        $this->assertEquals('new_email@mandalacare.com', $user->email);
        $this->assertTrue(Hash::check('newsecretpass123', $user->password));

        // Verify login with new credentials
        $this->post('/logout');

        $loginResponse = $this->post('/login', [
            'email' => 'new_email@mandalacare.com',
            'password' => 'newsecretpass123',
        ]);

        $loginResponse->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $user->delete();
    }

    public function test_ajax_update_profile_and_account(): void
    {
        $user = User::factory()->create([
            'name' => 'Ajax Tester',
            'email' => 'ajax_test@mandalacare.com',
        ]);

        // AJAX profile update
        $resProfile = $this->actingAs($user)->postJson('/profile/update', [
            'name' => 'Ajax Name Updated',
        ]);

        $resProfile->assertOk()
            ->assertJson([
                'success' => true,
                'name' => 'Ajax Name Updated',
            ]);

        // AJAX account update
        $resAccount = $this->actingAs($user)->postJson('/profile/account', [
            'email' => 'ajax_updated@mandalacare.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $resAccount->assertOk()
            ->assertJson([
                'success' => true,
                'email' => 'ajax_updated@mandalacare.com',
            ]);

        $user->delete();
    }

    public function test_validation_fails_for_mismatched_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/profile/account', [
            'email' => $user->email,
            'password' => 'newpassword1',
            'password_confirmation' => 'mismatchpass2',
        ]);

        $response->assertSessionHasErrors(['password']);

        $user->delete();
    }

    public function test_login_after_updating_profile_and_avatar(): void
    {
        $user = User::factory()->create([
            'email' => 'testflow@mandalacare.com',
            'password' => Hash::make('mypassword123'),
        ]);

        // 1. Log in
        $this->actingAs($user);

        // 2. Update profile name & avatar
        $avatarFile = UploadedFile::fake()->create('flow_avatar.png', 10, 'image/png');
        $this->post('/profile/update', [
            'name' => 'New Flow Name',
            'avatar' => $avatarFile,
        ]);

        $user->refresh();
        $this->assertEquals('New Flow Name', $user->name);

        // 3. Log out
        $this->post('/logout');
        $this->assertGuest();

        // 4. Log in again with same credentials
        $loginResponse = $this->post('/login', [
            'email' => 'testflow@mandalacare.com',
            'password' => 'mypassword123',
        ]);

        $loginResponse->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        // Cleanup
        if ($user->avatar && file_exists(public_path($user->avatar))) {
            @unlink(public_path($user->avatar));
        }
        $user->delete();
    }
}
