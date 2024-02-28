<?php

use Astrotomic\Tmdb\Models\TvGenre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::connection(TvGenre::connection())->create(TvGenre::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();

            $table->json('name');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection(TvGenre::connection())->dropIfExists(TvGenre::table());
    }
};
