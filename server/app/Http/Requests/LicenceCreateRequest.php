<?php

namespace App\Http\Requests;

use App\DTO\LicenceDTO;
use Illuminate\Foundation\Http\FormRequest;

class LicenceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'app_key' => 'required|string',
            'licence_key' => 'required|string',
            'expires_at' => 'required|date',
            'is_revoked' => 'required|integer',
        ];
    }
    public function toDTO(): LicenceDTO {
        return new LicenceDTO(
            appKey: $this->validated('app_key'),
            licenceKey: $this->validated('licence_key'),
            expiresAt: $this->validated('expires_at'),
            isRevoked: $this->validated('is_revoked')
        );
    }
}
