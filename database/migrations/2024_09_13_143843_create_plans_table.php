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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('icon');
            $table->string('background_shape');
            $table->double('monthly_price');
            $table->double('yearly_price');
            $table->longText('features')->nullable();
            $table->string('badge')->nullable();
            $table->longText('features');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_lans');
    }
};
