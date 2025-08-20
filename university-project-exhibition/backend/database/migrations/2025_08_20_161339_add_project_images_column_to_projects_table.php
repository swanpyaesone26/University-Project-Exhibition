<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('project_images')->nullable()->after('project_image');
        });

        // Copy old values into new JSON column
        DB::table('projects')
            ->whereNotNull('project_image')
            ->update([
                'project_images' => DB::raw("JSON_ARRAY(project_image)")
            ]);

        // Drop old column
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('project_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Restore old column
            $table->string('project_image')->nullable()->after('project_name');

            // Drop new column
            $table->dropColumn('project_images');
        });
    }
};
