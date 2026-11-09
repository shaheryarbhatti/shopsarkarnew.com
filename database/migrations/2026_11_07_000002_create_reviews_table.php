<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name');
            $table->unsignedTinyInteger('rating')->default(5); // 1..5
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('video_path')->nullable();     // uploaded video on our server
            $table->string('youtube_id')->nullable();     // after upload to YouTube
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->boolean('is_verified_buyer')->default(false);
            $table->timestamps();

            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
