<?php

use Astrotomic\Tmdb\Models\Person;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('credits', static function (Blueprint $table): void {
            $table->string('id')->primary();

            $table->foreignId('person_id')->constrained(Person::table());
            $table->morphs('media');

            $table->string('credit_type');
            $table->string('department')->nullable();
            $table->string('job')->nullable();
            $table->string('character')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
