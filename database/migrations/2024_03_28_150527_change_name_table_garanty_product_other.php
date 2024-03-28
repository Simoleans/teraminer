<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //garanty_products
        Schema::rename('garanties_products', 'garanty_products');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
