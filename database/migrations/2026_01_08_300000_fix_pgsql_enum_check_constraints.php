<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix PostgreSQL enum check constraints.
     *
     * When Laravel uses $table->enum(), PostgreSQL creates CHECK constraints.
     * Later migrations that ALTER COLUMN TYPE to VARCHAR(255) do NOT remove
     * the old CHECK constraint, causing inserts with new values to fail.
     *
     * This migration drops the stale constraints and re-creates them with
     * the full set of allowed values.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'pgsql') {
            return; // Only needed for PostgreSQL
        }

        // ─── orders.payment_method ───────────────────────────────
        // Drop old check constraint (may be named differently depending on Laravel version)
        $this->dropCheckConstraint('orders', 'payment_method');

        // Re-create with all valid values
        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_payment_method_check
            CHECK (payment_method::text = ANY (ARRAY[
                'cash'::text, 'card'::text, 'transfer'::text, 'credit'::text,
                'qr'::text, 'promptpay'::text, 'mixed'::text
            ]))
        ");

        // ─── orders.status ───────────────────────────────────────
        $this->dropCheckConstraint('orders', 'status');

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_status_check
            CHECK (status::text = ANY (ARRAY[
                'pending'::text, 'completed'::text, 'refunded'::text,
                'partial_refund'::text, 'cancelled'::text
            ]))
        ");

        // ─── orders.payment_status ───────────────────────────────
        $this->dropCheckConstraint('orders', 'payment_status');

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_payment_status_check
            CHECK (payment_status::text = ANY (ARRAY[
                'pending'::text, 'paid'::text, 'partial'::text, 'refunded'::text
            ]))
        ");

        // ─── activity_logs.action ────────────────────────────────
        $this->dropCheckConstraint('activity_logs', 'action');

        DB::statement("
            ALTER TABLE activity_logs
            ADD CONSTRAINT activity_logs_action_check
            CHECK (action::text = ANY (ARRAY[
                'login'::text, 'logout'::text, 'create'::text, 'update'::text,
                'delete'::text, 'print'::text, 'export'::text, 'download'::text,
                'restore'::text, 'view'::text, 'other'::text
            ]))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'pgsql') {
            return;
        }

        // Restore original constraints
        $this->dropCheckConstraint('orders', 'payment_method');
        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_payment_method_check
            CHECK (payment_method::text = ANY (ARRAY[
                'cash'::text, 'card'::text, 'transfer'::text, 'credit'::text
            ]))
        ");

        $this->dropCheckConstraint('activity_logs', 'action');
        DB::statement("
            ALTER TABLE activity_logs
            ADD CONSTRAINT activity_logs_action_check
            CHECK (action::text = ANY (ARRAY[
                'login'::text, 'logout'::text, 'create'::text, 'update'::text,
                'delete'::text, 'print'::text, 'export'::text, 'view'::text, 'other'::text
            ]))
        ");
    }

    /**
     * Drop all CHECK constraints on a given column of a table.
     */
    private function dropCheckConstraint(string $table, string $column): void
    {
        $constraints = DB::select("
            SELECT con.conname
            FROM pg_constraint con
            JOIN pg_class rel ON rel.oid = con.conrelid
            JOIN pg_namespace nsp ON nsp.oid = rel.relnamespace
            WHERE rel.relname = ?
              AND con.contype = 'c'
              AND pg_get_constraintdef(con.oid) LIKE ?
        ", [$table, "%{$column}%"]);

        foreach ($constraints as $constraint) {
            DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$constraint->conname}");
        }
    }
};
