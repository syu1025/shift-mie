<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('line_user_id');
            $table->date('shift_date');
            $table->string('shift_type')->nullable(); // 'time' or 'lecture'
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->json('lectures')->nullable(); // 講数の配列をJSONで保存
            $table->timestamps();

            $table->foreign('line_user_id')->references('line_user_id')->on('line_users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
};
