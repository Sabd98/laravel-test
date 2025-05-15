<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['pending', 'in_progress', 'done'])->default('pending');
            $table->date('due_date');

            // Relationships
            $table->uuid('assigned_to');
            $table->uuid('created_by');

            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('assigned_to')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Indexes
            $table->index('status');
            $table->index('due_date');
            $table->index(['assigned_to', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
