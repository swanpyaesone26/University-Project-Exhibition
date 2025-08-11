<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaborator_project', function (Blueprint $table) {
            $table->id('cp_id');
            $table->foreignId('project_id')->constrained('projects', 'project_id')->onDelete('cascade');
            $table->foreignId('collaborator_id')->constrained('collaborators', 'collaborator_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collaborator_project');
    }
};
