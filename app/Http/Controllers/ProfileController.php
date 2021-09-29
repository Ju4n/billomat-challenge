<?php

namespace App\Http\Controllers;

use App\Exceptions\ProfileException;
use App\Http\Controllers\APIBaseController;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Throwable;

class ProfileController extends APIBaseController
{
    public function getAllProfiles()
    {
        try {
            $profiles = app(ProfileService::class)->getAllProfiles();
            $response = $this->successJsonResponse($profiles);
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }

    public function getOneProfile(int $profileId)
    {
        try {
            $profiles = app(ProfileService::class)->getOneProfile($profileId);
            $response = $this->successJsonResponse($profiles);
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }

    public function createProfile(Request $request, $profileId)
    {
        try {
            $data = $request->json();
            $profile = app(ProfileService::class)->createProfile();
            $response = $this->successJsonResponse($profile, 201);
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }

    public function updateProfile(Request $request, int $profileId)
    {
        try {
            $data = $request->json();
            $profile = app(ProfileService::class)->updateProfile($profileId, $data);
            $response = $this->successJsonResponse($profile);
        } catch (ProfileException $e) {
            $response = $this->errorJsonResponse($e->getMessage(), $e->getCode());
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }

    public function deleteProfile(int $profileId)
    {
        try {
            $profile = app(ProfileService::class)->deleteProfile($profileId);
            $response = $this->successJsonResponse($profile);
        } catch (ProfileException $e) {
            $response = $this->errorJsonResponse($e->getMessage(), $e->getCode());
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }
}
