<?php

namespace Tests\Feature\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_list_all_projects()
    {
        // Create some projects
        Project::factory()->count(3)->create();

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_project()
    {
        // Create a student and user
        $student = Student::factory()->create();
        $user = User::factory()->create(['student_id' => $student->student_id]);
        
        // Get token for authentication
        $token = $user->createToken('auth-token')->plainTextToken;
        
        $projectData = [
            'user_id' => $user->user_id,
            'project_name' => 'Test Project',
            'project_detail' => 'This is a test project',
            'project_date' => '2025-10-15',
            'project_link' => 'https://github.com/test/project',
            'project_image' => 'image.jpg'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/projects', $projectData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'project_name' => 'Test Project',
                'project_detail' => 'This is a test project'
            ]);

        $this->assertDatabaseHas('projects', [
            'project_name' => 'Test Project',
            'user_id' => $user->user_id
        ]);
    }

    /** @test */
    public function it_can_retrieve_a_specific_project()
    {
        // Create a project
        $project = Project::factory()->create([
            'project_name' => 'Specific Project'
        ]);

        $response = $this->getJson('/api/projects/' . $project->project_id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'project_name' => 'Specific Project',
                'project_id' => $project->project_id
            ]);
    }

    /** @test */
    public function it_can_update_a_project()
    {
        // Create a student and user
        $student = Student::factory()->create();
        $user = User::factory()->create(['student_id' => $student->student_id]);
        
        // Create a project owned by the user
        $project = Project::factory()->create([
            'user_id' => $user->user_id,
            'project_name' => 'Original Project'
        ]);
        
        // Get token for authentication
        $token = $user->createToken('auth-token')->plainTextToken;
        
        $updateData = [
            'project_name' => 'Updated Project',
            'project_detail' => 'This project has been updated'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/projects/' . $project->project_id, $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'project_name' => 'Updated Project',
                'project_detail' => 'This project has been updated'
            ]);

        $this->assertDatabaseHas('projects', [
            'project_id' => $project->project_id,
            'project_name' => 'Updated Project'
        ]);
    }

    /** @test */
    public function it_can_delete_a_project()
    {
        // Create a student and user
        $student = Student::factory()->create();
        $user = User::factory()->create(['student_id' => $student->student_id]);
        
        // Create a project owned by the user
        $project = Project::factory()->create([
            'user_id' => $user->user_id
        ]);
        
        // Get token for authentication
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/projects/' . $project->project_id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('projects', [
            'project_id' => $project->project_id
        ]);
    }
}
