Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');

    // Tambahkan ini
    $table->string('phone')->nullable();
    $table->enum('role', ['admin', 'customer'])->default('customer');

    $table->rememberToken();
    $table->timestamps();
});
