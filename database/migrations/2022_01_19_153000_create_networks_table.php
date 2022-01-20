<?php

use Astrotomic\Tmdb\Models\Network;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::connection(Network::connection())->create(Network::table(), static function (Blueprint $table): void {
            $table->bigInteger('id')->unsigned()->primary();
            $table->string('headquarters')->nullable();
            $table->json('homepage')->nullable();
            $table->json('logo_path')->nullable();
            $table->json('languages')->nullable();
            $table->json('name')->nullable();
            $table->string('origin_country')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection(Network::connection())->dropIfExists(Network::table());
    }
};
