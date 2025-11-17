<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('technicien_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description');
            $table->string('type_appareil');
            $table->string('priorite')->default('normale'); // basse, normale, haute
            $table->string('statut')->default('nouvelle_demande');
            // Statuts: nouvelle_demande, diagnostic, en_reparation, termine, non_reparable
            $table->date('date_prevue')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
