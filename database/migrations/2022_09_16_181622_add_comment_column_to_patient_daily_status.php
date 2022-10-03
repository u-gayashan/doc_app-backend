<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient_daily_statuses', function (Blueprint $table) {
            $table->longText('comment')->after('what_will_keep_you_from_getting_bored')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_daily_statuses', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
};
