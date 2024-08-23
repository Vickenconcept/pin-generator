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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('name');
            $table->text('path'); // Path to the template image
            $table->integer('width'); // Path to the template width
            $table->integer('height'); // Path to the template height
            $table->json('editable_regions'); // JSON to store editable regions
            $table->enum('can_delete',[0,1])->default(0); 
            $table->enum('type', [
                'blocks', 
                'interior', 
                'multiimages', 
                'basics', 
                'educational', 
                'grey-style', 
                'pastel', 
                'recipe', 
                'shop', 
                'splatter', 
                'text-only', 
                'wavey', 
                'minimalist',
                'custom'
            ])->nullable();
            $table->boolean('favorite')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
