<?php

// database/migrations/{timestamp}_create_videos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Title of the video
            $table->text('url'); // URL of the video
            $table->enum('platform', ['Instagram', 'Facebook', 'YouTube']); // Platform of the video
            $table->string('thumbnail_url')->nullable(); // Thumbnail URL
            $table->string('username'); // Username of the video owner
            $table->text('description')->nullable(); // Optional description for the video
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key to categories table
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
