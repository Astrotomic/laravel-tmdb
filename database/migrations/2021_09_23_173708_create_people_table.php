<?php

use Astrotomic\Tmdb\Models\Person;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create(Person::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();

            $table->string('name')->nullable();
            $table->boolean('adult')->default(false);
            $table->json('also_known_as')->nullable();
            $table->date('birthday')->nullable();
            $table->date('deathday')->nullable();
            $table->smallInteger('gender')->unsigned();
            $table->string('homepage')->nullable();
            $table->string('imdb_id')->unique()->nullable();
            $table->string('known_for_department')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('profile_path')->nullable();
            $table->decimal('popularity')->unsigned()->nullable();

            $table->json('biography')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Person::table());
    }
};
