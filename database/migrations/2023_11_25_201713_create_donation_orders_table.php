<?php

use App\Models\Donation;
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
            $table->string('piid');
            // $table->string('token');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignIdFor(Donation::class, "donation_id");
            $table->decimal('price', 6)->nullable();

            $table->timestamps();

            $table->index(['piid']);
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
