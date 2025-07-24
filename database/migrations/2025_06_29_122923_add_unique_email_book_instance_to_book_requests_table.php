<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('book_requests', function (Blueprint $table) {
            $table->unique(['email', 'book_instance_id'], 'unique_email_book_instance');
        });
    }

    public function down()
    {
        Schema::table('book_requests', function (Blueprint $table) {
            $table->dropUnique('unique_email_book_instance');
        });
    }
};
