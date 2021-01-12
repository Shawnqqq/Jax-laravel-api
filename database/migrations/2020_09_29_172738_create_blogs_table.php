<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_tag_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('标签组名称');
            $table->unsignedBigInteger('sort')->nullable()->comment('排序');
            $table->timestamps();
        });

        Schema::create('blog_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->string('name')->nullable()->comment('标签名称');
            $table->foreign('group_id')->references('id')->on('blog_tag_groups')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->unsignedBigInteger('sort')->nullable()->comment('排序');
            $table->timestamps();
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->text('cover_url')->nullable()->comment('封面图');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
            $table->timestamps();
        });

        Schema::create('blog_tag_relation', function (Blueprint $table) {
            $table->unsignedBigInteger('blog_id');
            $table->unsignedBigInteger('tag_id');

            $table->foreign('blog_id')->references('id')->on('blogs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('blog_tags')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_tag_groups');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('blog_tag_relation');
    }
}
