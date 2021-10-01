<?php

use Astrotomic\Tmdb\Models\WatchProvider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::connection(WatchProvider::connection())->create(WatchProvider::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();

            $table->string('name');
            $table->integer('display_priority')->unsigned();
            $table->string('logo_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection(WatchProvider::connection())->dropIfExists(WatchProvider::table());
    }
};
