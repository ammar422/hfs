<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['Basic-init', 'Basic-init-plus', 'Premium-init', 'Pro-init', 'Essential', 'Basic', 'Premium', 'Pro', 'Ultimate']);
            $table->decimal('price', 65, 2);
            $table->enum('billing_period', ['monthly', 'yearly', 'quarterly', 'biannual', 'lifelong']);
            $table->integer('cv')->default(0);
            $table->json('features')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
