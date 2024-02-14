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
        Schema::create('form_types', function (Blueprint $table) {
            $table->increments('ftype_id');
			$table->string('ftype_name');
			$table->string('ftype_desc')->nullable();
            $table->timestamps();

			$table->index('ftype_name');
			$table->index('ftype_desc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_types');
    }
};
