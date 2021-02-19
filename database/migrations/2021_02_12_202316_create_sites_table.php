<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('location', '100')->nullable();
            $table->string('site', '100')->unique();
            $table->boolean('monitoring')->nullable();
            $table->string('gt_id', '20')->nullable();
            $table->string('poll_state_url', '200')->nullable();
            $table->string('state', '50')->nullable();
            $table->string('error', '200')->nullable();
            $table->string('report_url', '200')->nullable();
            $table->tinyInteger('pagespeed_score')->nullable();
            $table->tinyInteger('yslow_score')->nullable();
            $table->integer('html_bytes')->nullable();
            $table->integer('html_load_time')->nullable();
            $table->integer('page_bytes')->nullable();
            $table->integer('page_load_time')->nullable();
            $table->integer('page_elements')->nullable();
            $table->integer('redirect_duration')->nullable();
            $table->integer('connect_duration')->nullable();
            $table->integer('backend_duration')->nullable();
            $table->integer('first_paint_time')->nullable();
            $table->integer('dom_interactive_time')->nullable();
            $table->integer('dom_content_loaded_time')->nullable();
            $table->integer('dom_content_loaded_duration')->nullable();
            $table->integer('onload_time')->nullable();
            $table->integer('onload_duration')->nullable();
            $table->integer('fully_loaded_time')->nullable();
            $table->integer('rum_speed_index')->nullable();
            $table->string('report_pdf', '200')->nullable();
            $table->string('pagespeed', '200')->nullable();
            $table->string('har', '200')->nullable();
            $table->string('pagespeed_files', '200')->nullable();
            $table->string('report_pdf_full', '200')->nullable();
            $table->string('yslow', '200')->nullable();
            $table->string('screenshot', '200')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
