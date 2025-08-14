<?php

namespace Tests\Unit\Models;

use App\Models\Collaborator;
use App\Models\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollaboratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_collaborator_can_belong_to_many_projects()
    {
        // Create a collaborator
        $collaborator = Collaborator::factory()->create();
        
        // Create projects
        $project1 = Project::factory()->create();
        $project2 = Project::factory()->create();
        
        // Attach projects to the collaborator
        $collaborator->projects()->attach([$project1->project_id, $project2->project_id]);

        // Check if the collaborator belongs to many projects
        $this->assertCount(2, $collaborator->projects);
        $this->assertInstanceOf(Project::class, $collaborator->projects->first());
    }
}
