<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  protected function prepareForValidation()
  {
    $this->merge([
      'id' => $this->route('id'),
    ]);
  }

  public function rules(): array
  {
    return [
      'id'          => 'required|uuid',
      'title'       => 'sometimes|required|string|min:3|max:255',
      'description' => 'sometimes|nullable|string|max:1000',
      'completed'   => 'sometimes|required|boolean',
    ];
  }

  public function messages(): array
  {
    return [
      'id.uuid'           => 'The task ID format is invalid.',
      'id.required'       => 'The task ID is required.',
      'title.required'    => 'The title cannot be empty if provided.',
      'completed.boolean' => 'The completed field must be true or false.',
    ];
  }
}
