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
            $table->string('site', '100')->unique();
            $table->string('gt_id', '20');
            $table->string('poll_state_url', '200');
            $table->string('state', '50');
            $table->string('report_url', '200');
            $table->tinyInteger('pagespeed_score');
            $table->tinyInteger('yslow_score');
            $table->integer('html_bytes');
            $table->integer('html_load_time');
            $table->integer('page_bytes');
            $table->integer('page_load_time');
            $table->integer('page_elements');
            $table->integer('redirect_duration');
            $table->integer('connect_duration');
            $table->integer('backend_duration');
            $table->integer('first_paint_time');
            $table->integer('dom_interactive_time');
            $table->integer('dom_content_loaded_time');
            $table->integer('dom_content_loaded_duration');
            $table->integer('onload_time');
            $table->integer('onload_duration');
            $table->integer('fully_loaded_time');
            $table->integer('rum_speed_index');
            $table->string('report_pdf', '200');
            $table->string('pagespeed', '200');
            $table->string('har', '200');
            $table->string('pagespeed_files', '200');
            $table->string('report_pdf_full', '200');
            $table->string('yslow', '200');
            $table->string('screenshot', '200');
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
