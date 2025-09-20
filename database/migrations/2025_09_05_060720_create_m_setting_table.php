<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('m_setting', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('kta_path_before')->nullable();
      $table->string('kta_file_before')->nullable();
      $table->string('kta_path_now')->nullable();
      $table->string('kta_file_now')->nullable();

      $table->string('kta_back_path_before')->nullable();
      $table->string('kta_back_file_before')->nullable();
      $table->string('kta_back_path_now')->nullable();
      $table->string('kta_back_file_now')->nullable();

      $table->string('union_chairman')->nullable();
      $table->string('union_reg_number')->nullable();

      $table->string('union_logo_path')->nullable();
      $table->string('union_logo_file')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('m_setting');
  }
};
