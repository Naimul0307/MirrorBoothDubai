<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->decimal('price', 8, 2)->default(0);

            $table->text('description')->nullable();

            $table->string('slug')->unique();

            $table->integer('status')->default(1);

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('package_categories')
                ->nullOnDelete(); // cleaner than onDelete('set null')

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};