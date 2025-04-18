<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Check if all required fields exist
        if (!isset($row['name']) || !isset($row['nisn']) || !isset($row['password'])) {
            throw new \Exception("Format file Excel tidak sesuai. Pastikan kolom name, nisn, dan password ada.");
        }

        return new User([
            'name'     => $row['name'],
            'nisn'    => $row['nisn'],
            'password' => Hash::make($row['password']),
            'password_plain' => $row['password'],
            'jurusan' => $row['jurusan'] ?? null,
            'rata_rata' => $row['rata_rata'] ?? null,
            'status' => $row['status'] ?? 'Lulus',
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'nisn' => 'required|unique:users,nisn',
            'password' => 'required',
            'jurusan' => 'nullable',
            'rata_rata' => 'nullable|numeric|between:0,100',
            'status' => 'nullable|in:Lulus,Tidak Lulus',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Kolom nama wajib diisi.',
            'nisn.required' => 'Kolom NISN wajib diisi.',
            'nisn.unique' => 'NISN :input sudah terdaftar.',
            'password.required' => 'Kolom password wajib diisi.',
            'rata_rata.numeric' => 'Kolom rata-rata harus berupa angka.',
            'rata_rata.between' => 'Kolom rata-rata harus antara 0 hingga 100.',
            'status.in' => 'Status hanya boleh berisi "Lulus" atau "Tidak Lulus".',
        ];
    }
}
