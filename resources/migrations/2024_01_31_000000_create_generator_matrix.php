<?php

use EvolutionCMS\Models\SiteContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneratorMatrix extends Migration
{
    public function up()
    {
        try {
            Schema::create('generator_matrix', function (Blueprint $table) {
                $table->id();
                $table->integer('site_content_template');
                $table->integer('site_content_parent_id');
                $table->text('site_content_content');
                $table->string('pagetitle_template');
                $table->string('pagetitle_categories_tvlist');
                $table->string('pagetitle_entities_tvlist');
                $table->string('folders_id');
                $table->timestamps();
            });

//            Schema::create('generator_matrix_entities_folder', function (Blueprint $table) {
//                $table->id();
//                $table->unsignedBigInteger('matrix_id');
//                $table->unsignedInteger('entities_folder_id');
//                $table->foreign('matrix_id')->references('id')->on('generator_matrix');
//                $table->foreign('entities_folder_id')->references('id')->on('site_content');
//            });
        } catch (\Exception $e) {
            Schema::dropIfExists('generator_matrix_entities_folder');
            Schema::dropIfExists('generator_matrix');
            throw $e; // Перебрасываем исключение, чтобы оно было видно
        }
    }

    public function down()
    {
        Schema::dropIfExists('generator_matrix_entities_folder');
        Schema::dropIfExists('generator_matrix');
    }
}