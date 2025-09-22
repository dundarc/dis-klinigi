<?php
namespace App\Http\Requests\Api\V1;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('invoice'));
    }
    public function rules(): array
    {
        // Store request ile aynı kuralları kullanabiliriz
        return (new StoreInvoiceRequest())->rules();
    }
}