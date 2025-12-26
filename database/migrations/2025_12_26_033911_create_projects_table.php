<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('pgsql_ppdo')->create('projects', function (Blueprint $table) {
            $table->id();

            // Core fields
            $table->string('particulars');
            $table->year('year');
            $table->enum('status', ['Ongoing', 'Completed', 'Delayed'])->default('Ongoing');

            // Budget fields
            $table->decimal('budget_allocated', 15, 2)->default(0);
            $table->decimal('obligated_budget', 15, 2)->default(0);
            $table->decimal('budget_utilized', 15, 2)->default(0);

            // Performance metrics
            $table->decimal('utilization_rate', 5, 2)->default(0);
            $table->integer('completed')->default(0);
            $table->integer('delayed')->default(0);
            $table->integer('ongoing')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('db_ppd')->dropIfExists('projects');
    }
};
