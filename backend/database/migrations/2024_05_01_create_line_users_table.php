<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('line_users', function (Blueprint $table) {
            $table->string('line_user_id')->primary();
            $table->string('display_name')->nullable();
            $table->string('status_message')->nullable();
            $table->string('picture_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('line_users');
    }
};
