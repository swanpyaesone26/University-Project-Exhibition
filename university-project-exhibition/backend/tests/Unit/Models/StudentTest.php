<?php

namespace Tests\Unit\Models;

use App\Models\Student;
use App\Models\User;
use App\Models\Registration;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_student_has_correct_fillable_attributes()
    {
        $student = new Student();
        
        $fillable = ['uni_id', 'name', 'email', 'image', 'major', 'batch'];
        
        $this->assertEquals($fillable, $student->getFillable());
    }

    /** @test */
    public function a_student_has_one_user_relationship()
    {
        // Create a student
        $student = Student::factory()->create([
            'name' => 'Test Student',
            'email' => 'test@example.com',
            'uni_id' => 'UNI12345',
            'major' => 'Computer Science',
            'batch' => '2025'
        ]);

        // Create a user for the student
        $user = User::factory()->create([
            'student_id' => $student->student_id
        ]);

        // Check if the student has a user relationship
        $this->assertInstanceOf(User::class, $student->users);
        $this->assertEquals($user->user_id, $student->users->user_id);
    }

    /** @test */
    public function a_student_has_one_registration_relationship()
    {
        // Create a student
        $student = Student::factory()->create();
        
        // Create a registration for the student
        $registration = Registration::factory()->create([
            'student_id' => $student->student_id
        ]);

        // Check if the student has a registration relationship
        $this->assertInstanceOf(Registration::class, $student->registrations);
        $this->assertEquals($registration->registration_id, $student->registrations->registration_id);
    }
}
