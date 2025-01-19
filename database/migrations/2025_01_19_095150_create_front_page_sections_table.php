<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\FrontPage\FrontPageSectionStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('front_page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->foreignIdFor(FrontPageSectionImage::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('is_active')->default(FrontPageSectionStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_page_sections');
    }
};
