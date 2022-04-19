<?php

use Astrotomic\Tmdb\Models\Tv;
use Astrotomic\Tmdb\Models\TvGenre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::connection(Tv::connection())->create('tv_tv_genre', static function (Blueprint $table): void {
            $table->foreignId('tv_id')->constrained(Tv::table());
            $table->foreignId('tv_genre_id')->constrained(TvGenre::table());

            $table->unique(['tv_id', 'tv_genre_id']);
        });
    }

    public function down(): void
    {
        Schema::connection(Tv::connection())->dropIfExists('tv_tv_genre');
    }
};
