<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Book::class);
            $table->foreignIdFor(User::class);
            $table->date('loaned_at');
            $table->date('returned_at')->nullable();
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed');
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER update_loan_status AFTER INSERT ON loans
            FOR EACH ROW
            BEGIN
                UPDATE loans SET status =
                    CASE
                        WHEN NEW.returned_at = DATE("now") THEN "returned"
                        ELSE "borrowed"
                    END
                WHERE id = NEW.id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
