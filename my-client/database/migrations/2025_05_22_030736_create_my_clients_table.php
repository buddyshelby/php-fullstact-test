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
        Schema::create('my_clients', function (Blueprint $table) {
            $table->id();
            $table->char('name', 250);
            $table->char('slug', 100)->unique();
            $table->string('is_project', 30)->default('0');
            $table->char('self_capture', 1)->default('1');
            $table->char('client_prefix', 4);
            $table->string('client_logo')->default('no-image.jpg');
            $table->text('address')->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_clients');
    }
};
