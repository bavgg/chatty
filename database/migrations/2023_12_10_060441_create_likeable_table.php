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
        Schema::create('likeable', function(Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->integer('likeable_id'); // foreign key for 'polymorphic relationships'
            $table->string('likeable_type'); // refers to the Model of the liked item

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likeable');
    }
};
