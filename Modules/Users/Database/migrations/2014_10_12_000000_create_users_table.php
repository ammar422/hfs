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
            $table->foreignId('upline_id')->nullable()->constrained('users');
            $table->foreignId('left_leg_id')->nullable()->constrained('users');
            $table->foreignId('right_leg_id')->nullable()->constrained('users');
            $table->enum('leg_type', ['left', 'right', 'root'])->default('root');
            $table->decimal('cv', 12, 2)->default(0);
            $table->decimal('left_leg_cv', 65, 2)->default(0);
            $table->decimal('right_leg_cv', 65, 2)->default(0);
            $table->enum('placement', ['tree', 'tank'])->default('tank');


            $table->bigInteger('id_code')->unique();

            $table->string('email')->unique();
            $table->string('verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('mobile')->unique();
            $table->string('photo')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('password')->default('no_password_for_user')->nullable();
            $table->string('reset_token')->nullable();
            $table->foreignId('rank_id')->nullable()->constrained('ranks')->cascadeOnDelete()->cascadeOnUpdate();

            $table->decimal('total_earning', 65, 2)->default(0);
            $table->decimal('total_receive', 65, 2)->default(0);
            $table->decimal('total_bounce', 65, 2)->default(0);
            $table->decimal('total_transfer', 65, 2)->default(0);

            $table->enum('account_type', ['user', 'admin'])->default('user');
            $table->enum('account_status', ['pending', 'active', 'ban'])->default('active');
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
