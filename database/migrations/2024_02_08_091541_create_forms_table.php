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
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('form_id');
            $table->integer('stud_id')->unsigned();
            $table->integer('ftype_id')->unsigned();
            $table->string('fstud_fullname', 255);
            $table->string('fstud_nomat', 50);
            $table->string('fstud_nokp', 50)->nullable();
			$table->integer('prog_id')->unsigned();
            $table->string('fprog_name');
            $table->string('fprog_code')->nullable();
            $table->date('form_date');
            $table->integer('count_leave')->unsigned();
            $table->timestamp('start_leave')->nullable();
            $table->timestamp('end_leave')->nullable();
            $table->string('form_reason')->nullable();
            $table->enum('form_flag', ["BARU", "PENDING", "KIV", "LULUS", "TOLAK"]);
            $table->string('form_memo')->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->timestamps();

			$table->index('stud_id');
			$table->index('ftype_id');
			$table->index('fstud_fullname');
			$table->index('fstud_nomat');
			$table->index('fstud_nokp');
			$table->index('prog_id');
			$table->index('fprog_name');
			$table->index('fprog_code');
			$table->index('form_date');
			$table->index('count_leave');
			$table->index('start_leave');
			$table->index('end_leave');
			$table->index('form_reason');
			$table->index('form_flag');
			$table->index('form_memo');
			$table->index('created_by');
			$table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
