<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('claim_number')->unique();
            $table->string('medical_aid_provider');
            $table->string('medical_aid_number');
            $table->decimal('amount_claimed', 10, 2);
            $table->enum('status', ['Pending', 'Submitted', 'Approved', 'Rejected'])->default('Pending');
            $table->date('date_of_service');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};