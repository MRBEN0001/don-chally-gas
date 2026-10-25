<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSplitPaymentToPenjualanTable extends Migration
{
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->decimal('pay_cash', 15, 2)->default(0)->after('payment_method');
            $table->decimal('pay_transfer', 15, 2)->default(0)->after('pay_cash');
            $table->decimal('pay_card', 15, 2)->default(0)->after('pay_transfer');
        });
    }

    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['pay_cash', 'pay_transfer', 'pay_card']);
        });
    }
}