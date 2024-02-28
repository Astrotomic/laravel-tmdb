<?php

use Astrotomic\Tmdb\Models\Collection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::connection(Collection::connection())->create(Collection::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();

            $table->string('backdrop_path')->nullable();

            $table->json('name')->nullable();
            $table->json('overview')->nullable();
            $table->json('poster_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection(Collection::connection())->dropIfExists(Collection::table());
    }
};
