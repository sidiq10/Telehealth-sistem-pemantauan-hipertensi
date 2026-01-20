<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HealthRecord;
use App\Models\Feedback;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed badges first
        $this->call(BadgeSeeder::class);
        // Create 2 Doctors
        $dokter1 = User::create([
            'name' => 'Dr. Ahmad Suryanto',
            'email' => 'dokter1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'phone' => '0812345678',
            'birthdate' => '1970-05-15',
            'address' => 'Jl. Kesehatan No. 123, Jakarta',
        ]);

        $dokter2 = User::create([
            'name' => 'Dr. Siti Nurhaliza',
            'email' => 'dokter2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'phone' => '0812345679',
            'birthdate' => '1975-08-20',
            'address' => 'Jl. Medis No. 456, Surabaya',
        ]);

        // Create 5 Patients
        $pasien1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'pasien1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'phone' => '0812345680',
            'birthdate' => '1979-03-10',
            'address' => 'Jl. Merdeka No. 123, Bandung',
        ]);

        $pasien2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'pasien2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'phone' => '0812345681',
            'birthdate' => '1982-07-25',
            'address' => 'Jl. Cendana No. 789, Jakarta',
        ]);

        $pasien3 = User::create([
            'name' => 'Ahmad Ridho',
            'email' => 'pasien3@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'phone' => '0812345682',
            'birthdate' => '1965-11-30',
            'address' => 'Jl. Gatot Subroto No. 456, Medan',
        ]);

        $pasien4 = User::create([
            'name' => 'Fatimah Zahra',
            'email' => 'pasien4@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'phone' => '0812345683',
            'birthdate' => '1976-12-05',
            'address' => 'Jl. Ahmad Yani No. 321, Yogyakarta',
        ]);

        $pasien5 = User::create([
            'name' => 'Hasan Mukarom',
            'email' => 'pasien5@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'phone' => '0812345684',
            'birthdate' => '1970-01-15',
            'address' => 'Jl. Sudirman No. 789, Surabaya',
        ]);

        // Assign pasien to dokter (many-to-many relationship)
        $dokter1->patients()->attach([$pasien1->id, $pasien2->id, $pasien3->id]);
        $dokter2->patients()->attach([$pasien4->id, $pasien5->id, $pasien2->id]); // pasien2 ditangani 2 dokter

        // Create health records untuk setiap pasien (10 records per pasien)
        $this->createHealthRecords($pasien1);
        $this->createHealthRecords($pasien2);
        $this->createHealthRecords($pasien3);
        $this->createHealthRecords($pasien4);
        $this->createHealthRecords($pasien5);

        // Create feedback dari dokter ke pasien
        Feedback::create([
            'sender_id' => $dokter1->id,
            'receiver_id' => $pasien1->id,
            'message' => 'Pak Budi, tekanan darah Anda masih tinggi. Tolong kurangi asupan garam dan lebih banyak olahraga.',
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        Feedback::create([
            'sender_id' => $pasien1->id,
            'receiver_id' => $dokter1->id,
            'message' => 'Baik Dokter, saya akan berusaha. Apakah perlu ada obat tambahan?',
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        Feedback::create([
            'sender_id' => $dokter1->id,
            'receiver_id' => $pasien2->id,
            'message' => 'Ibu Siti, hasil pemeriksaan menunjukkan prehipertensi. Perlu diet khusus.',
            'is_read' => false,
        ]);

        Feedback::create([
            'sender_id' => $dokter2->id,
            'receiver_id' => $pasien4->id,
            'message' => 'Ibu Fatimah, data terakhir Anda masuk kategori hipertensi stage 1. Segera datang ke klinik.',
            'is_read' => false,
        ]);
    }

    /**
     * Create health records untuk pasien
     */
    private function createHealthRecords(User $pasien)
    {
        $data = [
            // Normal readings
            ['sistolik' => 115, 'diastolik' => 75, 'denyut_nadi' => 72, 'catatan' => 'Kondisi baik setelah istirahat'],
            ['sistolik' => 118, 'diastolik' => 77, 'denyut_nadi' => 70, 'catatan' => 'Normal'],
            
            // Prehipertensi readings
            ['sistolik' => 125, 'diastolik' => 82, 'denyut_nadi' => 75, 'catatan' => 'Setelah aktivitas ringan'],
            ['sistolik' => 130, 'diastolik' => 85, 'denyut_nadi' => 78, 'catatan' => 'Sedikit stress hari ini'],
            
            // Hipertensi Stage 1 readings
            ['sistolik' => 145, 'diastolik' => 90, 'denyut_nadi' => 80, 'catatan' => 'Setelah olahraga'],
            ['sistolik' => 148, 'diastolik' => 92, 'denyut_nadi' => 82, 'catatan' => 'Kondisi tidak baik'],
            
            // Hipertensi Stage 2 readings
            ['sistolik' => 160, 'diastolik' => 100, 'denyut_nadi' => 85, 'catatan' => 'Sangat stress'],
            ['sistolik' => 158, 'diastolik' => 98, 'denyut_nadi' => 84, 'catatan' => 'Lupa minum obat'],
            
            // Mixed readings
            ['sistolik' => 135, 'diastolik' => 88, 'denyut_nadi' => 76, 'catatan' => 'Setelah minum obat'],
            ['sistolik' => 140, 'diastolik' => 89, 'denyut_nadi' => 77, 'catatan' => 'Kondisi mulai membaik'],
        ];

        // Insert dengan timestamps di hari-hari sebelumnya
        foreach ($data as $index => $record) {
            $createdAt = Carbon::now()->subDays(10 - $index);
            
            HealthRecord::create([
                'patient_id' => $pasien->id,
                'sistolik' => $record['sistolik'],
                'diastolik' => $record['diastolik'],
                'denyut_nadi' => $record['denyut_nadi'],
                'catatan' => $record['catatan'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
