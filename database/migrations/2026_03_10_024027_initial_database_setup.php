<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Regions Table (MUST BE FIRST)
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region_code')->unique();
            $table->timestamps();
        });

        // 2. Users Table (Now it can safely reference regions)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();;
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('Member');
            $table->string('position')->nullable();
            $table->foreignId('region_id')->nullable()->constrained()->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();

            $table->unique(['region_id', 'role', 'position'], 'unique_regional_leader');
        });

        // 3. Provinces Table
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // 4. Organizations Table
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            // foreignId creates an unsignedBigInteger and sets up the relationship
            $table->foreignId('region_id')
                ->constrained()
                ->onDelete('cascade'); // If a region is deleted, its orgs are removed

            $table->string('name');
            $table->string('acronym')->nullable();
            $table->string('category')->default('LGU'); // e.g., LGU, NGO, Academe, PO
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Adding an index for faster filtering in the registration dropdown
            $table->index('region_id');
        });

        // 5. Members Table
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('sex');
            $table->string('civil_status');
            $table->date('dob');
            $table->string('contact_no');
            $table->string('email')->unique();

            // Address
            $table->foreignId('region_id')->constrained();
            $table->foreignId('province_id')->constrained();
            $table->string('city_municipality');
            $table->string('district')->nullable();
            $table->string('barangay');
            $table->string('zip_code', 4)->nullable();

            $table->string('member_type');
            $table->string('occupation')->nullable();
            $table->foreignId('organization_id')->nullable()->constrained();
            $table->string('specialization');
            $table->string('hvcdp_category')->nullable();
            $table->json('crops')->nullable();

            $table->json('services')->nullable();
            $table->string('internship')->nullable();
            $table->string('scholarship')->nullable();
            $table->string('lsa_level')->nullable();
            $table->string('lsa_type')->nullable();
            $table->string('training_course')->nullable();
            $table->timestamps();
        });

        // Default Laravel password tables
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('members');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('regions');
    }
};