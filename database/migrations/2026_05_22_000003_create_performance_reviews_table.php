<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();
            
            $table->foreignId('reviewer_id')
                ->constrained('employees');
            
            $table->string('review_period');
            
            $table->integer('quality_of_work')->comment('1-10');
            $table->integer('productivity')->comment('1-10');
            $table->integer('communication')->comment('1-10');
            $table->integer('teamwork')->comment('1-10');
            $table->integer('leadership')->comment('1-10');
            
            $table->decimal('overall_rating', 3, 2)->default(0);
            
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('goals')->nullable();
            $table->text('comments')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['employee_id', 'review_period']);
            $table->index('overall_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
