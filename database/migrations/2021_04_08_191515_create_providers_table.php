<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * */
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('ACL')->nullable();
            $table->string('PROVIDER');
            $table->string('PROVIDER_CITY');
            $table->string('PROVIDER_CODE');
            $table->string('PROVIDER_COUNTRY');
            $table->string('PROVIDER_EMAIL');
            $table->string('PROVIDER_FAX');
            $table->string('PROVIDER_ID');
            $table->string('PROVIDER_TYPE');
            $table->string('SPECIALIZATION');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
}

