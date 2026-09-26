<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create(TBL_LOGS, function (Blueprint $table) {
            $table->increments(COL_LOG_ID);
            $table->unsignedInteger(COL_LOG_CAR_ID)->nullable()->index();
            $table->text(COL_LOG_TEXT)->nullable();
            $table->unsignedInteger(COL_LOG_TARGET_USER_ID)->nullable()->index();
            $table->enum(COL_LOG_TARGET_USER_TYPE, [ENUM_LOG_TARGET_USER_TYPE_USER, ENUM_LOG_TARGET_USER_TYPE_ADMIN])->nullable()->index();
            $table->unsignedInteger(COL_LOG_TARGET_ID)->nullable()->index();
            $table->enum(COL_LOG_TARGET_TYPE, [
                ENUM_LOG_TARGET_TYPE_ADMIN,
                ENUM_LOG_TARGET_TYPE_ROLE,
                ENUM_LOG_TARGET_TYPE_SETTING,
            ])->nullable()->index();

            $table->unsignedInteger(COL_LOG_STEP)->nullable()->index();
            $table->enum(COL_LOG_TYPE, [
                ENUM_LOG_TYPE_STANDARD,
                ENUM_LOG_TYPE_ENVIRONMENT,
                ENUM_LOG_TYPE_INSURANCE,
                ENUM_LOG_TYPE_VIEW,
            ])->nullable();

            $table->datetime(COL_LOG_DATE)->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists(TBL_LOGS);
    }
};
