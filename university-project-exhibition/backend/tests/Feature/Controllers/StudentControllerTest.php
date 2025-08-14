<?php

namespace Tests\Feature\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_list_all_students()
    {
        // Create some students
        Student::factory()->count(5)->create();

        $response = $this->getJson('/api/students');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_can_create_a_student()
    {
        // Create an admin user if needed for authorization
        $student = Student::factory()->create();
        $user = User::factory()->create([
            'student_id' => $student->student_id
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;

        $studentData = [
            'uni_id' => 'UNI54321',
            'name' => 'New Student',
            'email' => 'newstudent@example.com',
            'major' => 'Data Science',
            'batch' => '2026'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/students', $studentData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'New Student',
                'email' => 'newstudent@example.com'
            ]);

        $this->assertDatabaseHas('students', [
            'uni_id' => 'UNI54321',
            'name' => 'New Student'
        ]);
    }

    /** @test */
    public function it_can_retrieve_a_specific_student()
    {
        // Create a student
        $student = Student::factory()->create([
            'name' => 'Specific Student',
            'email' => 'specific@example.com'
        ]);

        $response = $this->getJson('/api/students/' . $student->student_id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Specific Student',
                'email' => 'specific@example.com'
            ]);
    }

    /** @test */
    public function it_can_update_a_student()
    {
        // Create a student
        $student = Student::factory()->create([
            'name' => 'Original Student',
            'email' => 'original@example.com'
        ]);
        
        // Create user and get token
        $user = User::factory()->create([
            'student_id' => $student->student_id
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;

        $updateData = [
            'name' => 'Updated Student',
            'email' => 'updated@example.com'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/students/' . $student->student_id, $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Student',
                'email' => 'updated@example.com'
            ]);

        $this->assertDatabaseHas('students', [
            'student_id' => $student->student_id,
            'name' => 'Updated Student',
            'email' => 'updated@example.com'
        ]);
    }

    /** @test */
    public function it_can_delete_a_student()
    {
        // Create a student
        $student = Student::factory()->create();
        
        // Create user and get token (admin or authorized user)
        $adminStudent = Student::factory()->create();
        $adminUser = User::factory()->create([
            'student_id' => $adminStudent->student_id
        ]);
        $token = $adminUser->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/students/' . $student->student_id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('students', [
            'student_id' => $student->student_id
        ]);
    }

    /** @test */
    public function it_can_bulk_import_students()
    {
        // Create admin user for authorization
        $adminStudent = Student::factory()->create();
        $adminUser = User::factory()->create([
            'student_id' => $adminStudent->student_id
        ]);
        $token = $adminUser->createToken('auth-token')->plainTextToken;

        $studentsData = [
            'students' => [
                [
                    'uni_id' => 'UNI12345',
                    'name' => 'Bulk Student 1',
                    'email' => 'bulk1@example.com',
                    'major' => 'Computer Science',
                    'batch' => '2025'
                ],
                [
                    'uni_id' => 'UNI12346',
                    'name' => 'Bulk Student 2',
                    'email' => 'bulk2@example.com',
                    'major' => 'Data Science',
                    'batch' => '2025'
                ]
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/students/bulk', $studentsData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('students', [
            'uni_id' => 'UNI12345',
            'name' => 'Bulk Student 1'
        ]);
        
        $this->assertDatabaseHas('students', [
            'uni_id' => 'UNI12346',
            'name' => 'Bulk Student 2'
        ]);
    }
}
