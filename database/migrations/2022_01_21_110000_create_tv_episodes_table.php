<?php

use Astrotomic\Tmdb\Models\TvEpisode;
use Astrotomic\Tmdb\Models\TvSeason;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::connection(TvEpisode::connection())->create(TvEpisode::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();
            $table->date('air_date')->nullable();
            $table->json('name')->nullable();
            $table->json('overview')->nullable();
            $table->string('production_code')->nullable();
            $table->integer('season_number')->nullable();
            $table->string('still_path')->nullable();
            $table->decimal('vote_average')->nullable();
            $table->integer('vote_count')->default(0);
            $table->foreignId('tv_season_id')->nullable()->constrained(TvSeason::table());

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection(TvEpisode::connection())->dropIfExists(TvEpisode::table());
    }
};
