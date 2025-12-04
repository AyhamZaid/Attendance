<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained('training_sessions')->onDelete('cascade');
            $table->uuid('lms_user_id');
            $table->enum('mode', ['onsite', 'remote']);
            $table->float('geo_confidence')->default(0);
            $table->tinyInteger('risk_score')->default(0);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('ip_hash')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->timestamp('challenge_passed_at')->nullable();
            $table->timestamp('last_beacon_at')->nullable();
            $table->json('flags')->nullable();
            $table->timestamps();

            $table->unique(['training_session_id', 'lms_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}

