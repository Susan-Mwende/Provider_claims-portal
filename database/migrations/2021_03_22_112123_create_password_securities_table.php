<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * */
    public function up(): void
    {
        Schema::create('password_securities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->tinyInteger('password_expiry_days');
            $table->timestamp('password_updated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * */
    public function down(): void
    {
        Schema::dropIfExists('password_securities');
    }
}

