<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('menu_overrides', function (Blueprint $table) {
            $table->string('route_name')->nullable()->after('label'); // nome da rota Laravel
            $table->string('custom_url')->nullable()->after('route_name'); // URL absoluta/relativa
            $table->boolean('new_tab')->default(false)->after('custom_url'); // abrir em nova aba
        });
    }

    public function down(): void {
        Schema::table('menu_overrides', function (Blueprint $table) {
            $table->dropColumn(['route_name', 'custom_url', 'new_tab']);
        });
    }
};
