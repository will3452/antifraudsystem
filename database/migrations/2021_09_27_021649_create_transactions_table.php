<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('reference_number');
            $table->string('sender_first_name');
            $table->string('sender_last_name');
            $table->string('sender_middle_name');
            $table->string('sender_address');
            $table->string('sender_mobile');
            $table->string('receiver_first_name');
            $table->string('receiver_last_name');
            $table->string('receiver_middle_name');
            $table->string('receiver_address');
            $table->string('receiver_mobile');
            $table->text('purpose')->nullable();
            $table->string('relationship')->nullable();
            $table->decimal('amount');
            $table->decimal('fee');
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
        Schema::dropIfExists('transactions');
    }
}
