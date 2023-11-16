<?php

use App\Models\Post;
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
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string("url", 255);
            $table->string("mimetype", 30);
            $table->string("extension", 10)->nullable();
            $table->unsignedBigInteger("size")->nullable();
            $table->foreignIdFor(User::class, "user_id");
            $table->foreignId('post_id')->nullable()->constrained();

            // $table->unsignedBigInteger('uploadable_id')->nullable();
            // $table->string('uploadable_type')->nullable();
            $table->timestamps();

            // $table->index(['uploadable_id', 'uploadable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
