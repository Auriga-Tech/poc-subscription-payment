<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->string('razorpay_subscription_id')->nullable();//Razorpay Subscription id
            $table->string('stripe_subscription_id')->nullable();//Stripe Subscription id
            $table->string('amount');
            $table->string('country_code'); 
            $table->integer('status'); //1->Created, 2->Active, 3->Completed, 4->Cancelled, 5->Hold
            $table->date('expired_at');
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
        Schema::dropIfExists('subscriptions');
    }
}
