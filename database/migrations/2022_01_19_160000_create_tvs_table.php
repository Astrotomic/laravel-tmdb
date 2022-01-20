<?php

use Astrotomic\Tmdb\Models\Tv;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::connection(Tv::connection())->create(Tv::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();
            $table->string('backdrop_path')->nullable();
            $table->json('episode_run_time')->nullable();
            $table->date('first_air_date')->nullable();
            $table->json('homepage')->nullable();
            $table->boolean('in_production')->nullable();
            $table->json('languages')->nullable();
            $table->date('last_air_date')->nullable();
            $table->json('name')->nullable();
            $table->integer('number_of_episodes')->nullable();
            $table->integer('number_of_seasons')->nullable();
            $table->json('origin_country')->nullable();
            $table->string('original_language', 2)->nullable();
            $table->string('original_name')->nullable();
            $table->json('overview')->nullable();
            $table->decimal('popularity')->unsigned()->nullable();
            $table->json('poster_path')->nullable();
            $table->json('production_companies')->nullable();
            $table->json('production_countries')->nullable();
            $table->json('spoken_languages')->nullable();
            $table->string('status')->nullable();
            $table->json('tagline')->nullable();
            $table->string('type')->nullable();
            $table->decimal('vote_average')->nullable();
            $table->integer('vote_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection(Tv::connection())->dropIfExists(Tv::table());
    }
};
