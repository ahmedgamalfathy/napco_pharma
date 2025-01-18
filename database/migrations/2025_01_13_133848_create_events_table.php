<?php

use App\Enums\Event\EventStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(EventStatus::NOT_PUBLISHED->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
