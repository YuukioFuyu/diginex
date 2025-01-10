<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->double('price');
            $table->string('light_icon')->nullable();
            $table->string('dark_icon')->nullable();
            $table->string('cover')->nullable();
            $table->string('video_cover')->nullable();
            $table->string('video_link')->nullable();
            $table->longText('description');
            $table->longText('faq_content')->nullable();
            $table->text('side_content')->nullable();
            $table->string('tags')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
