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
        Schema::create('commission_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('commission_wallet_id')->nullable()->constrained('commission_wallets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('token_wallet_id')->nullable()->constrained('token_wallets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'done', 'rejected'])->default('pending');
            $table->text('rejected_reasons')->nullable();
            $table->enum('transaction_type', ['credit', 'debit', 'cach', 'vodafon_cash', 'to_token_wallet', 'visa' , 'commission_transaction']);
            $table->decimal('transaction_fees', 65, 2)->default(0);
            $table->dateTime('paid_at')->nullable();
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
        Schema::dropIfExists('commission_wallet_transactions');
    }
};
