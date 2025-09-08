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
    Schema::create('m_member', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('m_user_id');
      $table->foreign('m_user_id')->references('id')->on('m_user')->onDelete('cascade');
      $table->string('reg_number')->nullable();
      $table->string('national_id_number')->nullable();
      $table->string('name')->nullable();
      $table->string('birth_place')->nullable();
      $table->date('birth_date')->nullable();
      $table->enum('gender', ['male', 'female'])->nullable();
      $table->text('address')->nullable();
      $table->string('rt')->nullable();
      $table->string('rw')->nullable();
      $table->string('village')->nullable();
      $table->string('district')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('post_code')->nullable();
      $table->string('phone')->nullable();
      $table->string('email')->nullable();
      $table->string('religion')->nullable();
      $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->nullable();
      $table->boolean('is_married')->nullable();
      $table->string('hobbies')->nullable();
      $table->string('pp_path')->nullable();
      $table->string('pp_file')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('m_member');
  }
};
