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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('freelancer_id');
            $table->unsignedBigInteger('seeker_id');
            $table->unsignedBigInteger('admin_id')->default(1);
            $table->text('contract_content');
            $table->text('freelancer_public_key')->nullable();
            $table->text('freelancer_signature')->nullable();
            $table->text('seeker_public_key')->nullable();
            $table->text('seeker_signature')->nullable();
            $table->text('admin_public_key')->nullable();
            $table->text('admin_signature')->nullable();

            $table->timestamps();


            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seeker_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
