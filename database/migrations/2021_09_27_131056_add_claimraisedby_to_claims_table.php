<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaimraisedbyToClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * */
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('claimraisedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('claimraisedby');
        });
    }
}

