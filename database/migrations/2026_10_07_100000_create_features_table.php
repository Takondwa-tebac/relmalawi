<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->index();
            $table->string('group');
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('icon')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['page_slug', 'group', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
