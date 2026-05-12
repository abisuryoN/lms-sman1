<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SiswaImport;
use App\Imports\GuruImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function siswaForm()
    {
        return view('admin.import.siswa');
    }

    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new SiswaImport();
            Excel::import($import, $request->file('file'));

            $count = $import->getRowCount();
            return redirect()->route('admin.siswa.index')
                ->with('success', "Berhasil import {$count} data siswa.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function guruForm()
    {
        return view('admin.import.guru');
    }

    public function importGuru(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new GuruImport();
            Excel::import($import, $request->file('file'));

            $count = $import->getRowCount();
            return redirect()->route('admin.guru.index')
                ->with('success', "Berhasil import {$count} data guru.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
