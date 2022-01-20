<?php

use Astrotomic\Tmdb\Models\Network;
use Astrotomic\Tmdb\Models\Tv;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::connection(Tv::connection())->create('network_tv', static function (Blueprint $table): void {
            $table->foreignId('tv_id')->constrained(Tv::table());
            $table->foreignId('network_id')->constrained(Network::table());

            $table->unique(['tv_id', 'network_id']);
        });
    }

    public function down(): void
    {
        Schema::connection(Tv::connection())->dropIfExists('network_tv');
    }
};
