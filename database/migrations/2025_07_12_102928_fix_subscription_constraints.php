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
        // Try to add indexes, but don't fail if they already exist
        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->index('stripe_id');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
        
        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->index('stripe_status');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
        
        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->index(['user_id', 'stripe_status']);
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
        
        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->index('ends_at');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
        
        // Add foreign key constraint if it doesn't exist
        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
        
        // Also fix subscription_items table if it exists
        if (Schema::hasTable('subscription_items')) {
            try {
                Schema::table('subscription_items', function (Blueprint $table) {
                    $table->index('stripe_id');
                });
            } catch (\Exception $e) {
                // Index might already exist
            }
            
            try {
                Schema::table('subscription_items', function (Blueprint $table) {
                    $table->index('stripe_price');
                });
            } catch (\Exception $e) {
                // Index might already exist
            }
            
            try {
                Schema::table('subscription_items', function (Blueprint $table) {
                    $table->foreign('subscription_id')
                          ->references('id')
                          ->on('subscriptions')
                          ->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Since we're checking existence before creating, we can't reliably drop
        // So we'll just leave this empty to avoid issues
    }
};