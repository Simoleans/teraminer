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
        Schema::table('invoices', function (Blueprint $table) {
            //status
            $table->tinyInteger('status')->default(1);
        });

        Schema::create('garanties', function (Blueprint $table) {
            $table->id();
            //invoice foreign key
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('garanties_product', function (Blueprint $table) {
            $table->id();

            //garanty foreign
            $table->foreignId('garanty_id')->constrained()->onDelete('cascade');
            //product foreign key
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            //serial
            $table->string('serial')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garanties');
    }
};
