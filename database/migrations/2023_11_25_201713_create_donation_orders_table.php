<?php

use App\Models\User;
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
        Schema::create('donation_orders', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->decimal('total_price', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_orders');
    }
};
