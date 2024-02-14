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
        Schema::create('students', function (Blueprint $table) {
            $table->increments('stud_id');
            $table->integer('user_id')->unsigned();
            $table->string('stud_fullname');
            $table->string('stud_nomat', 50);
            $table->string('stud_nokp', 50)->nullable();
            $table->integer('prog_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
            $table->timestamps();

			$table->index('user_id');
			$table->index('stud_fullname');
			$table->index('stud_nomat');
			$table->index('stud_nokp');
			$table->index('prog_id');
			$table->index('created_by');
			$table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
