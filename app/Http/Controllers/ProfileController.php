<?php

namespace App\Http\Controllers;

use App\Exceptions\ProfileException;
use App\Http\Controllers\APIBaseController;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProfileController extends APIBaseController
{
    public function getProfiles()
    {
        try {
            $profiles = app(ProfileService::class)->getProfiles();
            $response = $this->successJsonResponse($profiles);
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }

    public function getProfile(int $profileId)
    {
        try {
            $profiles = app(ProfileService::class)->getProfile($profileId);
            $response = $this->successJsonResponse($profiles);
        } catch (ProfileException $e) {
            $response = $this->errorJsonResponse($e->getMessage(), $e->getCode());
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }

    public function createProfile(Request $request)
    {

        // check if content header is application/json
        if (!$request->isJson()) {
            return $this->errorJsonResponse('invalid content header, must be application/json', 400);
        }

        try {
            // validate the json input
            $rules = [
                'name' => ['required', 'string'],
                'age' => ['required', 'integer', 'min:1', 'max:120'],
                'biography' => ['sometimes', 'string'],
                'image_url' => ['sometimes', 'string', 'url']
            ];
            $this->validate($request, $rules);
            // create
            $data = $request->json()->all();
            $profile = app(ProfileService::class)->createProfile($data);
            $response = $this->successJsonResponse($profile, 201);
        } catch (ValidationException $e) {
            $response = $this->errorJsonResponse($e->errors(), 422);
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }

    public function updateProfile(Request $request, int $profileId)
    {
        // check if content header is application/json
        if (!$request->isJson()) {
            return $this->errorJsonResponse('invalid content header, must be application/json', 400);
        }

        try {
            // validate the json input
            $rules = [
                'name' => ['sometimes', 'string'],
                'biography' => ['sometimes', 'string'],
                'age' => ['sometimes', 'integer', 'min:1', 'max:120'], 
                'image_url' => ['sometimes', 'string', 'url']
            ];
            $this->validate($request, $rules);
            // update
            $data = $request->json()->all();
            $profile = app(ProfileService::class)->updateProfile($profileId, $data);
            $response = $this->successJsonResponse($profile);
        } catch (ProfileException $e) {
            $response = $this->errorJsonResponse($e->getMessage(), $e->getCode());
        } catch (ValidationException $e) {
            $response = $this->errorJsonResponse($e->errors(), 422);
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

    public function getProfilesAverageAge()
    {
        try {
            $averageAge = app(ProfileService::class)->getProfilesAverageAge();
            $response = $this->successJsonResponse($averageAge);
        } catch (Throwable $e) {
            $response = $this->errorJsonResponse($e->getMessage(), 500);
        }

        return $response;
    }
}
