<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('menu_overrides', function (Blueprint $table) {
            $table->string('parent_key')->nullable()->after('order'); // aponta para a key do pai
        });
    }
    public function down(): void {
        Schema::table('menu_overrides', function (Blueprint $table) {
            $table->dropColumn('parent_key');
        });
    }
};
