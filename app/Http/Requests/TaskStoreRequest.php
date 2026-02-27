<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'title'       => 'required|string|min:3|max:255',
      'description' => 'nullable|string|max:1000',
    ];
  }

  public function messages(): array
  {
    return [
      'title.required' => 'The task title is required.',
      'title.min'      => 'The title must be at least 3 characters long.',
      'description.max' => 'The description cannot exceed 1000 characters.',
    ];
  }
}
