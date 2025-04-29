<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('line_messages', function (Blueprint $table) {
            $table->string('user_name')->nullable()->after('line_user_id');
        });
    }

    public function down()
    {
        Schema::table('line_messages', function (Blueprint $table) {
            $table->dropColumn('user_name');
        });
    }
};