<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    private int $rowCount = 0;

    public function model(array $row)
    {
        // Find or create kelas based on name
        $tahunAktif = TahunAjaran::aktif()->first();
        $kelas = null;

        if (!empty($row['kelas']) && $tahunAktif) {
            $kelas = Kelas::firstOrCreate(
                ['nama_kelas' => trim($row['kelas']), 'tahun_ajaran_id' => $tahunAktif->id]
            );
        }

        // Check if user/siswa already exists
        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        // Create user account (password = NIS)
        $user = User::create([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make((string) $row['nis']),
            'role' => 'siswa',
        ]);

        $this->rowCount++;

        // Create siswa profile
        return new Siswa([
            'user_id' => $user->id,
            'kelas_id' => $kelas?->id,
            'nis' => (string) $row['nis'],
            'nama' => $row['nama'],
            'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? 'L') === 'P' ? 'P' : 'L',
            'status' => 'aktif',
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string',
            'nis' => 'required',
            'email' => 'required|email',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
