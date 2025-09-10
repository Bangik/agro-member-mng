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
    Schema::create('t_complaint', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->uuid('m_member_id')->constrained('m_member')->onDelete('cascade');
      $table->uuid('m_user_id')->constrained('m_user')->onDelete('cascade')->nullable();
      $table->string('code');
      $table->string('title');
      $table->text('complaint');
      $table->text('response')->nullable();
      $table->date('response_at')->nullable();
      $table->date('resolved_at')->nullable();
      $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('t_complaint');
  }
};
