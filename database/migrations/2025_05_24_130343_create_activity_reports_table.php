<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('activity_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('materi');
            $table->string('tempat');
            $table->string('photo_url')->nullable(); // nanti bisa diisi dari kamera
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('activity_reports');
    }
};

