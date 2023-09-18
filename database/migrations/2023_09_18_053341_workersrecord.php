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
        Schema::create('workersrecord', function (Blueprint $table) {
            $table->id();
            $table->string('userid');
            $table->string('total_hours_in_office');
            $table->string('total_out_of_office');
            $table->string('attendance');
            $table->string('totaltime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
