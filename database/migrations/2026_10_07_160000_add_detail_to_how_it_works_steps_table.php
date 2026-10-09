<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('how_it_works_steps', function (Blueprint $table) {
            $table->text('detail')->nullable()->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('how_it_works_steps', function (Blueprint $table) {
            $table->dropColumn('detail');
        });
    }
};
