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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_name', 100);
            $table->string('password');
            $table->string('level_id', 10);
            $table->enum('user_status', ["ACTIVE", "INACTIVE"]);
            $table->string('user_email', 155)->nullable();
            $table->string('user_nophone', 50)->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
            $table->timestamps();

			$table->index('user_name');
			$table->index('level_id');
			$table->index('user_status');
			$table->index('user_email');
			$table->index('user_nophone');
			$table->index('created_by');
			$table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
