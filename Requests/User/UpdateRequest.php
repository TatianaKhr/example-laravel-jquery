<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Dto\User\UpdateDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateRequest extends FormRequest
{
    final public function authorize(): bool
    {
        return true;
    }

    final public function rules(): array
    {
        return [
            'name' => ['sometimes', 'alpha_dash', 'string'],
            'birthday' => ['sometimes', 'date', 'before:today'],
            'email' => ['sometimes', 'string', 'email'],
            'password' => [
                'sometimes',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'current_password' => [
                'sometimes',
                'string',
                'min:8',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail(__('The current password is incorrect.'));
                    }
                },
            ],
            'avatar_path' => ['sometimes', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:20480'],
        ];
    }

    final public function dto(): UpdateDto
    {
        return new UpdateDto(
            name: $this->input('name'),
            birthday: $this->input('birthday'),
            email: $this->input('email'),
            password: $this->input('password'),
            currentPassword: $this->input('current_password'),
            avatarPath: $this->file('avatar_path'),
        );
    }
}
