<?php

namespace App\Imports;

use App\Seller;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
HeadingRowFormatter::default('none');

class SellersImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
    use Importable;

    public function model(array $row)
    {
        return new Seller([
            'type_document_id' => $row['ID Documento'],
            'document' => $row['Número documento'],
            'name' => $row['Nombre'],
            'surname' => $row['Apellido'],
            'email' => $row['Correo electrónico'],
            'cell_phone_number' => $row['Teléfono celular'],
            'phone_number' => $row['Teléfono fijo'],
            'address' => $row['Dirección'],
        ]);
    }

    public function rules(): array{
        return[
            'Número documento' => [
                'required',
                'numeric',
                'digits_between:8,10',
                Rule::unique('sellers', 'document')
            ],
            'ID Documento' => 'required|numeric|exists:type_documents,id',
            'Nombre' => 'required|string|min:3|max:50',
            'Apellido' => 'required|string|min:3|max:50',
            'Correo electrónico' => [
                'required',
                'email',
                'string',
                'min:5',
                'max:100',
                Rule::unique('sellers', 'email')
            ],
            'Teléfono celular' => 'required|numeric|digits:10',
            'Teléfono fijo' => 'nullable|numeric|digits:7',
            'Dirección' => 'required|string|min:5|max:100',
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }
}