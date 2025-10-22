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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', [
                'applied',
                'under_review',
                'phone_screening',
                'interview_scheduled',
                'interviewed',
                'technical_interview',
                'final_interview',
                'offer_received',
                'offer_accepted',
                'offer_declined',
                'rejected',
                'withdrawn'
            ])->default('applied');
            $table->date('applied_date');
            $table->text('notes')->nullable();
            $table->string('resume_url')->nullable();
            $table->string('cover_letter_url')->nullable();
            $table->decimal('salary_expectation', 10, 2)->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
