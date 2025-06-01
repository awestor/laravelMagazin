<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_discounts', function (Blueprint $table) {

            $table->dropForeign(['product_id']);
            $table->dropForeign(['discount_id']);

            $table->dropPrimary();


            $table->id('product_discounts_id')->first();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('product_discounts', function (Blueprint $table) {
            $table->dropColumn('product_discounts_id'); 
            $table->primary(['discount_id', 'product_id']); 
        });
    }
};

