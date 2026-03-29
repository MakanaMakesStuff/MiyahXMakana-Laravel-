<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        // 1. Perform the VIP list check before allowing the reset
        $allowed = array_map('trim', explode(',', config('app.allowed_emails')));

        if (! in_array($user->email, $allowed)) {
            throw ValidationException::withMessages([
                'email' => ['This account is no longer authorized to perform this action.'],
            ]);
        }

        // 2. Standard Password Validation
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        // 3. Update the password
        $user->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}