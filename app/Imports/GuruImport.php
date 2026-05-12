<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GuruImport implements ToModel, WithHeadingRow, WithValidation
{
    private int $rowCount = 0;

    public function model(array $row)
    {
        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        $user = User::create([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make((string) $row['nip']),
            'role' => 'guru',
        ]);

        $this->rowCount++;

        return new Guru([
            'user_id' => $user->id,
            'nip' => (string) $row['nip'],
            'nama' => $row['nama'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string',
            'nip' => 'required',
            'email' => 'required|email',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
