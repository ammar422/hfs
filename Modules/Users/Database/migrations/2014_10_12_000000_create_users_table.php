<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {



            $table->id('id');
            $table->string('full_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            
            $table->foreignId('sponsor_id')->nullable()->constrained('users'); 
            $table->enum('placement', ['left', 'right', 'tank'])->default('tank'); 
            $table->decimal('cv', 65, 2)->default(0); 
            $table->decimal('left_leg_cv', 65, 2)->default(0); 
            $table->decimal('right_leg_cv', 65, 2)->default(0); 

            $table->string('email')->unique();
            $table->string('verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('mobile')->unique();
            $table->string('photo')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('password')->default('no_password_for_user')->nullable();
            $table->string('reset_token')->nullable();

            
            $table->enum('account_type', ['user', 'admin'])->default('user');
            $table->enum('account_status', ['pending', 'active', 'ban'])->default('pending');
            $table->longText('ban_reason')->nullable();
            $table->foreignId('admin_group_id')->nullable()->constrained('admin_groups')->cascadeOnDelete()->cascadeOnUpdate();

            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
