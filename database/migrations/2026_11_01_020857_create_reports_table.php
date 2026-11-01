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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('meal_distribution_id')->constrained('meal_distributions')->onDelete('cascade');
            $table->date('tanggal_kejadian');
            $table->text('deskripsi_kejadian');
            $table->integer('jumlah_terdampak')->default(1);
            $table->string('bukti_file')->nullable();
            $table->enum('status', ['diajukan', 'ditinjau', 'diteruskan', 'selesai'])->default('diajukan');
            $table->text('catatan_pemantau')->nullable();
            $table->string('diteruskan_ke')->nullable();
            $table->timestamp('tanggal_diteruskan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
