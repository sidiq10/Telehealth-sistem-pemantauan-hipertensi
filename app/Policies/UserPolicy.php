<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if dokter dapat view pasien
     */
    public function viewPasien(User $dokter, User $pasien, User $requestingDokter): bool
    {
        // Pastikan dokter yang request adalah si dokter
        if ($dokter->id !== $requestingDokter->id) {
            return false;
        }

        // Dokter hanya bisa view pasien yang ditangani
        return $dokter->patients()->where('users.id', $pasien->id)->exists();
    }
}
