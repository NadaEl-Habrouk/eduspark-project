<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('category'); 
            $table->enum('mode', ['individual', 'team']); 
            
            $table->json('title'); 
            $table->json('description'); 
            
            $table->json('options')->nullable();
            $table->integer('correct_option')->nullable();
            $table->string('badge'); // أضفنا عمود البادج هنا أيضاً
            
            $table->integer('points')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};