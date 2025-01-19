<?php

use Illuminate\Support\Facades\Schema;
use App\Enums\FrontPage\FrontPageStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('front_pages', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_active')->default(FrontPageStatus::ACTIVE->value);
            $table->string('controller_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_pages');
    }
};
