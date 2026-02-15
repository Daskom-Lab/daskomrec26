<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Stage;
use App\Models\CaasStage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Hash;

class CaasImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;
    
    private $importedCount = 0;
    private $skippedCount = 0;
    /**
     * Transform a row into a User model
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Cast NIM to string (Excel may store it as numeric)
        $nim = (string) $row['nim'];
        
        // Check if user with this NIM already exists
        $existingUser = User::where('nim', $nim)->first();
        if ($existingUser) {
            $this->skippedCount++;
            return null; // Skip duplicate users
        }

        // Create the user
        $user = User::create([
            'nim' => $nim,
            'password' => Hash::make($nim), // Default password is NIM
        ]);

        // Create profile
        $user->profile()->create([
            'name' => $row['nama'],
            'major' => $row['jurusan'],
            'class' => $row['kelas'],
            'gender' => $row['jenis_kelamin'] ?? $row['jenis kelamin'] ?? null,
        ]);

        // Create CaasStage with Administration stage (default)
        $administrationStage = Stage::where('name', 'Administration')->first();
        if ($administrationStage) {
            CaasStage::create([
                'user_id' => $user->id,
                'stage_id' => $administrationStage->id,
                'status' => 'PROSES',
            ]);
        }

        $this->importedCount++;
        return $user;
    }

    /**
     * Define validation rules for each row
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nim' => 'required', // Accept both string and numeric
            'nama' => 'required|string',
            'jurusan' => 'nullable',
            'kelas' => 'nullable',
            'jenis_kelamin' => 'nullable',
        ];
    }

    /**
     * Custom validation messages
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'nim.required' => 'NIM is required',
            'nama.required' => 'Name is required',
        ];
    }

    /**
     * Get imported count
     *
     * @return int
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Get skipped count
     *
     * @return int
     */
    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
