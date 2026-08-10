<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // This migrate is for user restrictions about his health

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->json('allergies')->nullable()->after('medical_conditions'); //حساسية قد تكون اكثر من شيء
            $table->string('dietary_preference')->nullable()->after('allergies'); //النظام الغذائي مثل الشخص النباتي
            $table->boolean('disclaimer_accepted')->default(false)->after('dietary_preference');
            //الموافقة على الشروط الطبية قبل بدء التطبيق

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['allergies', 'dietary_preference', 'disclaimer_accepted']);
        });
    }
};
