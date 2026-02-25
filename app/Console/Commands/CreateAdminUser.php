<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create
                            {--name= : The admin user\'s name}
                            {--email= : The admin user\'s email}
                            {--password= : The admin user\'s password}';

    protected $description = 'Create a new admin user or promote an existing user to admin';

    public function handle(): int
    {
        $email = $this->option('email') ?? $this->ask('Email');

        $existing = User::where('email', $email)->first();

        if ($existing) {
            if ($existing->is_admin) {
                $this->info("User [{$email}] is already an admin.");

                return self::SUCCESS;
            }

            if ($this->confirm("User [{$email}] already exists. Promote to admin?", true)) {
                $existing->update(['is_admin' => true]);
                $existing->assignRole('super-admin');
                $this->info("User [{$email}] has been promoted to admin.");
            }

            return self::SUCCESS;
        }

        $name = $this->option('name') ?? $this->ask('Name');
        $password = $this->option('password') ?? $this->secret('Password');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $user->assignRole('super-admin');

        $this->info("Admin user [{$email}] created successfully with the [super-admin] role.");

        return self::SUCCESS;
    }
}
