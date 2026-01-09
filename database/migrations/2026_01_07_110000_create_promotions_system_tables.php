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
        // Member Tiers (ระดับสมาชิก)
        Schema::create('member_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // Bronze, Silver, Gold, Platinum, VIP
            $table->string('name_th')->nullable();       // Thai name
            $table->decimal('min_spending', 12, 2)->default(0);  // Minimum spending to reach this tier
            $table->decimal('discount_percent', 5, 2)->default(0); // Default discount percentage
            $table->integer('points_multiplier')->default(1);  // Points earning multiplier
            $table->string('color')->default('#6B7280');  // Badge color
            $table->string('icon')->nullable();           // Icon for display
            $table->json('benefits')->nullable();         // Additional benefits
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add tier to customers
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'member_tier_id')) {
                $table->foreignId('member_tier_id')->nullable()->after('is_active')->constrained()->nullOnDelete();
            }
        });

        // Promotions (โปรโมชั่น)
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_th')->nullable();
            $table->string('code')->unique()->nullable();  // Coupon code if applicable
            $table->text('description')->nullable();
            $table->text('description_th')->nullable();

            // Promotion Type
            $table->enum('type', [
                'percentage',      // % off
                'fixed_amount',    // Fixed amount off
                'buy_x_get_y',     // Buy X Get Y Free
                'bundle',          // Bundle pricing
                'free_item',       // Free item with purchase
                'tier_discount',   // Member tier specific discount
            ])->default('percentage');

            // Discount Values
            $table->decimal('discount_value', 10, 2)->default(0);   // Percentage or fixed amount
            $table->decimal('min_purchase', 12, 2)->default(0);     // Minimum purchase amount
            $table->decimal('max_discount', 12, 2)->nullable();     // Maximum discount cap

            // Buy X Get Y specific fields
            $table->integer('buy_quantity')->nullable();    // Buy X quantity
            $table->integer('get_quantity')->nullable();    // Get Y quantity free

            // Date/Time restrictions
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->json('active_days')->nullable();        // [0,1,2,3,4,5,6] - Days of week
            $table->time('start_time')->nullable();         // Start time of day
            $table->time('end_time')->nullable();           // End time of day

            // Usage limits
            $table->integer('usage_limit')->nullable();     // Total uses allowed
            $table->integer('usage_count')->default(0);     // Current usage count
            $table->integer('per_customer_limit')->nullable(); // Limit per customer

            // Targeting
            $table->foreignId('member_tier_id')->nullable()->constrained()->nullOnDelete();  // Specific tier only
            $table->boolean('new_customers_only')->default(false);
            $table->boolean('stackable')->default(false);   // Can stack with other promotions

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Show prominently
            $table->string('image_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['start_date', 'end_date']);
            $table->index(['is_active', 'start_date', 'end_date']);
        });

        // Promotion-Product relationship (which products the promotion applies to)
        Schema::create('promotion_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['included', 'excluded', 'free_item', 'bundle_item'])->default('included');
            $table->integer('quantity')->nullable();  // For bundle items
            $table->timestamps();

            $table->unique(['promotion_id', 'product_id', 'type']);
        });

        // Promotion-Category relationship (apply to entire categories)
        Schema::create('promotion_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['included', 'excluded'])->default('included');
            $table->timestamps();

            $table->unique(['promotion_id', 'category_id']);
        });

        // Bundle Deals
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_th')->nullable();
            $table->text('description')->nullable();
            $table->decimal('bundle_price', 12, 2);         // Special bundle price
            $table->decimal('original_price', 12, 2)->nullable(); // Original total price
            $table->decimal('savings', 12, 2)->nullable();  // Amount saved
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('stock_limit')->nullable();     // Limited stock
            $table->integer('sold_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Bundle Items
        Schema::create('bundle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->unique(['bundle_id', 'product_id']);
        });

        // Promotion Usage History
        Schema::create('promotion_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['promotion_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
        Schema::dropIfExists('bundle_items');
        Schema::dropIfExists('bundles');
        Schema::dropIfExists('promotion_categories');
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('promotions');

        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'member_tier_id')) {
                $table->dropForeign(['member_tier_id']);
                $table->dropColumn('member_tier_id');
            }
        });

        Schema::dropIfExists('member_tiers');
    }
};
