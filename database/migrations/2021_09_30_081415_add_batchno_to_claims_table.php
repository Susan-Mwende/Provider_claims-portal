<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchnoToClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * */
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('batchno')->nullable();
            $table->boolean('sendalert')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('batchno');
            $table->boolean('sendalert');
        });
    }
}

