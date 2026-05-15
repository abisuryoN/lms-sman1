<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\GuruKelas;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin ─────────────────────────────
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@sman1tajurhalang.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // ── 2. Tahun Ajaran ──────────────────────
        $ta = TahunAjaran::create([
            'nama_tahun' => '2025/2026',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        // ── 3. Mata Pelajaran ────────────────────
        $mapelData = [
            ['MTK', 'Matematika'], ['BIN', 'Bahasa Indonesia'], ['BIG', 'Bahasa Inggris'],
            ['FIS', 'Fisika'], ['KIM', 'Kimia'], ['BIO', 'Biologi'],
            ['SEJ', 'Sejarah'], ['PKN', 'PKN'], ['PAI', 'PAI'],
            ['PJK', 'Penjaskes'], ['SNB', 'Seni Budaya'], ['INF', 'Informatika'],
        ];
        $mapels = [];
        foreach ($mapelData as [$kode, $nama]) {
            $mapels[$kode] = Mapel::create(['kode_mapel' => $kode, 'nama_mapel' => $nama]);
        }

        // ── 4. Kelas ────────────────────────────
        $kelasNames = [
            'X IPA 1', 'X IPA 2', 'X IPS 1',
            'XI IPA 1', 'XI IPA 2', 'XI IPS 1',
            'XII IPA 1', 'XII IPA 2', 'XII IPS 1',
        ];
        $kelasList = [];
        foreach ($kelasNames as $name) {
            $kelasList[$name] = Kelas::create([
                'nama_kelas' => $name,
                'tahun_ajaran_id' => $ta->id,
            ]);
        }

        // ── 5. Guru ──────────────────────────────
        $guruData = [
            ['Budi Santoso', 'cc', 'budi@sman1tajurhalang.sch.id'],
            ['Siti Aminah', '198602022011012002', 'siti@sman1tajurhalang.sch.id'],
            ['Ahmad Hidayat', '198703032012011003', 'ahmad@sman1tajurhalang.sch.id'],
        ];
        $guruList = [];
        foreach ($guruData as [$nama, $nip, $email]) {
            $user = User::create([
                'name' => $nama, 'email' => $email,
                'password' => Hash::make($nip), 'role' => 'guru',
            ]);
            $guruList[] = Guru::create([
                'user_id' => $user->id, 'nip' => $nip, 'nama' => $nama,
            ]);
        }

        // Assign guru ke kelas
        GuruKelas::create(['guru_id' => $guruList[0]->id, 'kelas_id' => $kelasList['X IPA 1']->id, 'mapel_id' => $mapels['MTK']->id, 'tahun_ajaran_id' => $ta->id]);
        GuruKelas::create(['guru_id' => $guruList[0]->id, 'kelas_id' => $kelasList['XI IPA 1']->id, 'mapel_id' => $mapels['MTK']->id, 'tahun_ajaran_id' => $ta->id]);
        GuruKelas::create(['guru_id' => $guruList[1]->id, 'kelas_id' => $kelasList['X IPA 1']->id, 'mapel_id' => $mapels['BIN']->id, 'tahun_ajaran_id' => $ta->id]);
        GuruKelas::create(['guru_id' => $guruList[2]->id, 'kelas_id' => $kelasList['X IPA 1']->id, 'mapel_id' => $mapels['FIS']->id, 'tahun_ajaran_id' => $ta->id]);

        // Set wali kelas
        $kelasList['X IPA 1']->update(['wali_kelas_id' => $guruList[0]->user_id]);

        // ── 6. Siswa ─────────────────────────────
        $siswaData = [
            ['Andi Pratama', '12001', 'andi@siswa.sch.id', 'X IPA 1', 'L'],
            ['Bintang Nugraha', '12002', 'bintang@siswa.sch.id', 'X IPA 1', 'L'],
            ['Citra Dewi', '12003', 'citra@siswa.sch.id', 'X IPA 1', 'P'],
            ['Dina Safitri', '12004', 'dina@siswa.sch.id', 'X IPA 1', 'P'],
            ['Eko Saputra', '12005', 'eko@siswa.sch.id', 'X IPA 2', 'L'],
            ['Fani Rahmawati', '12006', 'fani@siswa.sch.id', 'XI IPA 1', 'P'],
        ];

        foreach ($siswaData as [$nama, $nis, $email, $kelas, $jk]) {
            $user = User::create([
                'name' => $nama, 'email' => $email,
                'password' => Hash::make($nis), 'role' => 'siswa',
            ]);
            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $kelasList[$kelas]->id,
                'nis' => $nis, 'nama' => $nama,
                'jenis_kelamin' => $jk, 'status' => 'aktif',
            ]);
        }
    }
}
