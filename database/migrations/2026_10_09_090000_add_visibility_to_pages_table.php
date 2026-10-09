<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Switched-off pages return a 404 to the public and disappear from the site's links.
            $table->boolean('is_active')->default(true)->after('slug');
            // Independent of is_active: an active page can still be left out of the navbar.
            $table->boolean('show_in_nav')->default(true)->after('is_active');
            $table->string('nav_label')->nullable()->after('show_in_nav');
            // 0 = use the built-in order; any other number sorts ahead of that, lowest first.
            $table->unsignedSmallInteger('nav_sort')->default(0)->after('nav_label');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'show_in_nav', 'nav_label', 'nav_sort']);
        });
    }
};
