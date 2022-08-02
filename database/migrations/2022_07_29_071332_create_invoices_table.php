<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('razorpay_invoice_id')->nullable();//Razorpay Invoice id
            $table->string('stripe_invoice_id')->nullable();//Stripe Invoice id
            $table->string('invoice_number');
            $table->date('paid_at')->nullable();
            $table->integer('status')->default(1); //1->Pending, 2->Paid, 3->Void, 4->Cancelled
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
        Schema::dropIfExists('invoices');
    }
}
