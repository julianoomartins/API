<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();     // mesmo 'key' do _menu
            $table->string('label')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('hidden')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('menu_overrides');
    }
};
