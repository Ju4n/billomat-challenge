<?php

namespace App\Services;

use App\Exceptions\ProfileException;
use App\Models\Profile;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProfileService
{
    public function getAllProfiles(): Collection
    {
        return Profile::all();
    }

    public function getOneProfile(int $profileId): ?Profile
    {
        return Profile::findOne($profileId);
    }

    public function createProfile(array $data): Profile
    {
        try {
            $profile = new Profile($data);
            $profile->saveOrFail();
        } catch (Throwable $e) {
            throw new ProfileException();
        }

        return $profile->fresh();
    }

    public function updateProfile(int $profileId, $data): Profile
    {
        try {
            $profile = Profile::findOrFail($profileId);
            $profile->update($data);
        } catch (ModelNotFoundException $e) {
            throw new ProfileException('the profile you are trying to update, doesnt exists', 404);
        }

        return $profile->fresh();
    }

    public function deleteProfile(int $profileId): ?array
    {
        try {
            $profile = Profile::findOrFail($profileId);
            $profile->delete();
        } catch (ModelNotFoundException $e) {
            throw new ProfileException('the profile you are trying to delete, doesnt exists', 404);
        }

        return ['deleted' => true, 'profile' => $profile];
    }
}
