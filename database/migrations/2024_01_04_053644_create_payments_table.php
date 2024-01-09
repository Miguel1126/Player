<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // $table->string('order');
            $table->string('total');
            $table->string('date');
            $table->foreignId('credit_card_id')
            ->nullable()
            ->constrained('credit_cards')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            $table->foreignId('paypal_id')
            ->nullable()
            ->constrained('paypals')
            ->cascadeOnUpdate()
            ->nullOnDelete();
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
        Schema::dropIfExists('payments');
    }
};
