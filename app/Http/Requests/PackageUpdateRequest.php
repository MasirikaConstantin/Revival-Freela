<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'price' => 'required',
            'term' => 'required',
            'number_of_service_add' => 'required',
            'number_of_service_featured' => 'required',
            'number_of_form_add' => 'required',
            'number_of_service_order' => 'required',
            'live_chat_status' => 'required',
            'qr_builder_status' => 'required',
            'recommended' => 'required',
            'trial_days' => $this->is_trial == "1" ? 'required' : '',
            'qr_code_save_limit' => $this->qr_builder_status == "1" ? 'required' : '',
        ];
    }
    public function messages(): array
    {
        return [
            'trial_days.required' => 'Trial days is required when trial option is checked',
            'qr_code_save_limit.required' => 'QR code save limit feild is required when qr builder status option is checked'
        ];
    }
}
