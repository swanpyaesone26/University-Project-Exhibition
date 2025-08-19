<?php

namespace Tests\Feature\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_register()
    {
        // Create a student to associate with the new user
        $student = Student::factory()->create();

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'student_id' => $student->student_id
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'user_id',
                    'name',
                    'email',
                    'student_id'
                ],
                'token'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'student_id' => $student->student_id
        ]);
    }

    /** @test */
    public function a_user_can_login()
    {
        // Create a student
        $student = Student::factory()->create();
        
        // Create a user
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
            'student_id' => $student->student_id
        ]);

        $loginData = [
            'email' => 'login@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'user_id',
                    'name',
                    'email',
                    'student_id'
                ],
                'token'
            ]);
    }

    /** @test */
    public function a_user_can_logout()
    {
        // Create a student
        $student = Student::factory()->create();
        
        // Create a user
        $user = User::factory()->create([
            'student_id' => $student->student_id
        ]);

        // Get token by logging in
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out'
            ]);
        
        // Check that the token has been deleted
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    /** @test */
    public function invalid_login_returns_error()
    {
        $loginData = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }
}
