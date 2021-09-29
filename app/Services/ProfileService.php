<?php

namespace App\Services;

use App\Exceptions\ProfileException;
use App\Models\Profile;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProfileService
{
    public function getProfiles(): Collection
    {
        return Profile::all();
    }

    public function getProfile(int $profileId): ?Profile
    {
        try {
            $profile = Profile::findOrFail($profileId);
        } catch (ModelNotFoundException $e) {
            throw new ProfileException('the profile you are looking for, does not exist', 404);
        }

        return $profile;
    }

    public function createProfile(array $data): Profile
    {
        $profile = new Profile($data);
        $profile->saveOrFail();

        return $profile->fresh();
    }

    public function updateProfile(int $profileId, array $data): Profile
    {
        try {
            $profile = Profile::findOrFail($profileId);
            $profile->update($data);
        } catch (ModelNotFoundException $e) {
            throw new ProfileException('The profile you are trying to update, does not exist.', 404);
        }

        return $profile->fresh();
    }

    public function deleteProfile(int $profileId): Profile
    {
        try {
            $profile = Profile::findOrFail($profileId);
            $profile->delete();
        } catch (ModelNotFoundException $e) {
            throw new ProfileException('The profile you are trying to delete, does not exist.', 404);
        }

        return $profile;
    }

    public function getProfilesAverageAge(): float
    {
        $profile = Profile::select(DB::raw('AVG(age) as avgAge'))->first();

        return (float) sprintf("%01.1f", $profile->avgAge);
    }
}
