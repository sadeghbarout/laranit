<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create(TBL_UPLOAD_FILES, function (Blueprint $table) {
            $table->increments(COL_UPLOAD_FILE_ID);
            $table->unsignedInteger(COL_UPLOAD_FILE_USER_ID)->nullable();
            $table->string(COL_UPLOAD_FILE_FILE, 128)->nullable();
            $table->enum(COL_UPLOAD_FILE_TYPE, [
				ENUM_UPLOAD_FILE_TYPE_ID_CARD_IMAGE,
            ]);
            $table->unsignedInteger(COL_UPLOAD_FILE_TARGET_ID)->nullable();
            $table->enum(COL_UPLOAD_FILE_TARGET_TYPE, [
				ENUM_UPLOAD_FILE_TARGET_TYPE_USER,
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists(TBL_UPLOAD_FILES);
    }
};
