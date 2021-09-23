<?php

use Astrotomic\Tmdb\Models\Movie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::create(Movie::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();

            $table->boolean('adult')->default(false);
            $table->boolean('video')->default(false);
            $table->string('backdrop_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->bigInteger('budget')->nullable();
            $table->bigInteger('revenue')->nullable();
            $table->string('homepage')->nullable();
            $table->string('imdb_id')->unique()->nullable();
            $table->string('original_language', 2)->nullable();
            $table->string('original_title')->nullable();
            $table->decimal('popularity')->unsigned()->nullable();
            $table->date('release_date')->nullable();
            $table->integer('runtime')->nullable();
            $table->decimal('vote_average')->nullable();
            $table->integer('vote_count')->default(0);
            $table->json('production_countries')->nullable();
            $table->string('status')->nullable();

            $table->json('title')->nullable();
            $table->json('tagline')->nullable();
            $table->json('overview')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Movie::table());
    }
};
