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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->string("title", 255);
            $table->text("description");

            $table->foreignIdFor(User::class, "user_id");
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');

            $table->unsignedBigInteger('ancestor_id');
            $table->string('ancestor_type');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['commentable_id', 'commentable_type']);
            $table->index(['ancestor_id', 'ancestor_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
