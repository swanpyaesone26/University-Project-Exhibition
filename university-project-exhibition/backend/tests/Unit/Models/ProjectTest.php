<?php

namespace Tests\Unit\Models;

use App\Models\Project;
use App\Models\User;
use App\Models\Collaborator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_has_correct_fillable_attributes()
    {
        $project = new Project();
        
        $fillable = [
            'user_id',
            'project_name',
            'project_detail',
            'project_date',
            'project_link',
            'project_image',
            'popularity',
            'liked',
        ];
        
        $this->assertEquals($fillable, $project->getFillable());
    }

    /** @test */
    public function a_project_belongs_to_user()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a project for the user
        $project = Project::factory()->create([
            'user_id' => $user->user_id,
            'project_name' => 'Test Project'
        ]);

        // Check if the project belongs to the user
        $this->assertInstanceOf(User::class, $project->users);
        $this->assertEquals($user->user_id, $project->users->user_id);
    }

    /** @test */
    public function a_project_can_have_many_collaborators()
    {
        // Create a project
        $project = Project::factory()->create([
            'project_name' => 'Collaboration Project'
        ]);
        
        // Create collaborators
        $collaborator1 = Collaborator::factory()->create();
        $collaborator2 = Collaborator::factory()->create();
        
        // Attach collaborators to the project
        $project->collaborators()->attach([$collaborator1->collaborator_id, $collaborator2->collaborator_id]);

        // Check if the project has collaborators
        $this->assertCount(2, $project->collaborators);
        $this->assertInstanceOf(Collaborator::class, $project->collaborators->first());
    }

    /** @test */
    public function a_project_has_correct_primary_key()
    {
        $project = new Project();
        $this->assertEquals('project_id', $project->getKeyName());
    }
}
