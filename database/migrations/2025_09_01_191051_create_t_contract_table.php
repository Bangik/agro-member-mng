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
    Schema::create('t_contract', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('m_member_id');
      $table->uuid('m_part_id');
      $table->foreign('m_member_id')->references('id')->on('m_member')->onDelete('cascade');
      $table->foreign('m_part_id')->references('id')->on('m_part')->onDelete('cascade');
      $table->string('contract_number')->nullable();
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('t_contract');
  }
};
