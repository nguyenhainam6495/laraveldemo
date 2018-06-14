<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('TieuDe');
            $table->mediumText('TIeuDeKhongDau');
            $table->mediumText('TomTat');
            $table->mediumText('NoiDung');
            $table->text('Hinh')->nullable();
            $table->text('NoiBat');
            $table->text('SoLuotXem');
            $table->text('idLoaiTin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
