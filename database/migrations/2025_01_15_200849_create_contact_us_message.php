<?php

use App\Enums\ContactUs\SenderType;
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
        Schema::create('contact_us_message', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->timestamp('is_read')->nullable();
            $table->boolean('is_admin')->default(SenderType::CUSTOMER->value);
            $table->foreignId('contact_us_id')->nullable()->constrained('contact_us')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us_message');
    }
};
