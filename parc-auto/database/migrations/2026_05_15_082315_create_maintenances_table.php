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
       Schema::create('maintenances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
        $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null'); // TA NOUVELLE COLONNE
        $table->string('type');
        $table->date('date_intervention');
        $table->integer('kilometrage_au_moment_de_l_acte');
        $table->integer('prochain_kilometrage_rappel')->nullable();
        $table->decimal('cout', 10, 2)->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
