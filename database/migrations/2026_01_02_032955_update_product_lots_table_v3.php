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
        Schema::table('product_lots', function (Blueprint $table) {
            if (!Schema::hasColumn('product_lots', 'gr_reference')) {
                $table->string('gr_reference')->nullable()->after('supplier');
            }
            if (!Schema::hasColumn('product_lots', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_lots', function (Blueprint $table) {
            if (Schema::hasColumn('product_lots', 'supplier_id')) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn(['supplier_id']);
            }
            if (Schema::hasColumn('product_lots', 'gr_reference')) {
                $table->dropColumn(['gr_reference']);
            }
        });
    }
};
