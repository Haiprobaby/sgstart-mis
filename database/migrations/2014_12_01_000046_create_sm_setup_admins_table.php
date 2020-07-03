<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SmSetupAdmin;
class CreateSmSetupAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_setup_admins', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->nullable()->comment('1 purpose, 2 complaint type, 3 source, 4 Reference');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();
            

$table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('restrict');
        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_setup_admins');
    }
}
