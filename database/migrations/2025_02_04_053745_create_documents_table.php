<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->float('iva')->nullable();
            $table->float('total')->nullable();
            $table->float('subtotal')->nullable();
            $table->string('enterprise')->nullable();
            $table->string('customer')->nullable();
            $table->string('source')->nullable();
            $table->date('date')->nullable();

            $table->unsignedBigInteger('orderNumber')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
