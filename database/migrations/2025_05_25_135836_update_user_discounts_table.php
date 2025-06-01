<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_discounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['discount_id']);


            $table->dropPrimary();


            $table->id('user_discounts_id')->first();
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('user_discounts', function (Blueprint $table) {
            $table->dropColumn('user_discounts_id'); 
            $table->primary(['user_id', 'discount_id']); 
        });
    }
};


