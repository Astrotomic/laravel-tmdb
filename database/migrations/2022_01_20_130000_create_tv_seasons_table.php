<?php

use Astrotomic\Tmdb\Models\Tv;
use Astrotomic\Tmdb\Models\TvSeason;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::connection(TvSeason::connection())->create(TvSeason::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();
            $table->date('air_date')->nullable();
            $table->json('name')->nullable();
            $table->json('overview')->nullable();
            $table->json('poster_path')->nullable();
            $table->integer('season_number')->nullable();
            $table->foreignId('tv_id')->nullable()->constrained(Tv::table());

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection(TvSeason::connection())->dropIfExists(TvSeason::table());
    }
};
