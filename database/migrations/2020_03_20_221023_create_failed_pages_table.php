<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('site_id');
            $table->string('url');
            $table->string('exception')->nullable();
            $table->timestamps();
        });

        Schema::table('failed_pages', function (Blueprint $table) {
            $table->foreign('site_id')
                  ->references('id')->on('sites')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_pages');
    }
}
