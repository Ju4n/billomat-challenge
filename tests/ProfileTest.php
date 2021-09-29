<?php

use App\Models\Profile;

class ProfileTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function testGetProfilesSuccess()
    {
        // create mock profiles
        Profile::factory()->count(10)->create();

        $response = $this->get('/profiles');
        // Assert Response
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            'success',
            'data' => [
                "*" => [
                    'id',
                    'name',
                    'age',
                    'biography',
                    'image_url'
                ]
            ]
        ]);
    }

    public function testGetOneProfileSuccess()
    {
        // create mock profiles
        $profiles = Profile::factory()->count(10)->create();
        $profile = $profiles->first();

        $response = $this->get('/profiles/' . $profile->id);
        // Assert Response
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'age',
                'biography',
                'image_url'
            ]
        ]);
        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['data'];
        $this->assertEquals($data['id'], $profile->id);
        $this->assertEquals($data['name'], $profile->name);
        $this->assertEquals($data['age'], $profile->age);
        $this->assertEquals($data['biography'], $profile->biography);
        $this->assertEquals($data['image_url'], $profile->image_url);
    }

    public function testCreateProfileSuccess()
    {
        $data = [
            'name' => 'john tester',
            'age' => 42,
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ];

        $response = $this->json('POST', '/profiles', $data);
        // Assert Response
        $response->assertResponseStatus(201);
        $response->seeJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'age',
                'biography',
                'image_url'
            ]
        ]);

        // Assert data in DB
        $this->seeInDatabase('profile', [
            'name' => 'john tester',
            'age' => 42,
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ]);

        $responseData = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $responseData = $responseData['data'];
        $this->assertEquals($responseData['name'], $data['name']);
        $this->assertEquals($responseData['age'], $data['age']);
        $this->assertEquals($responseData['biography'], $data['biography']);
        $this->assertEquals($responseData['image_url'], $data['image_url']);
    }

    public function testCreateProfileWhitoutNameFailed()
    {
        $data = [
            'age' => 42,
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ];

        $response = $this->json('POST', '/profiles', $data);
        // Assert Response
        $response->assertResponseStatus(422);
        $response->seeJsonStructure([
            'success',
            'error'
        ]);

        // Assert data in DB
        $this->notseeInDatabase('profile', [
            'age' => 42,
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['error'];
        $this->assertEquals(current($data['name']), 'The name field is required.');
    }

    public function testCreateProfileWhitoutAgeFailed()
    {
        $data = [
            'name' => 'john tester',
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ];

        $response = $this->json('POST', '/profiles', $data);
        // Assert Response
        $response->assertResponseStatus(422);
        $response->seeJsonStructure([
            'success',
            'error'
        ]);

        // Assert data in DB
        $this->notseeInDatabase('profile', [
            'name' => 'john tester',
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['error'];
        $this->assertEquals(current($data['age']), 'The age field is required.');
    }

    public function testCreateProfileWithStringInAgeFailed()
    {
        $data = [
            'name' => 'john tester',
            'age' => 'hello',
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ];

        $response = $this->json('POST', '/profiles', $data);
        // Assert Response
        $response->assertResponseStatus(422);
        $response->seeJsonStructure([
            'success',
            'error'
        ]);

        // Assert data in DB
        $this->notseeInDatabase('profile', [
            'name' => 'john tester',
            'age' => 'hello',
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['error'];
        $this->assertEquals(current($data['age']), 'The age must be an integer.');
    }

    public function testCreateProfileWithInvalidImageUrlFailed()
    {
        $data = [
            'name' => 'john tester',
            'age' => 34,
            'biography' => 'I like to test',
            'image_url' => 's3.com/photo.jpg'
        ];

        $response = $this->json('POST', '/profiles', $data);
        // Assert Response
        $response->assertResponseStatus(422);
        $response->seeJsonStructure([
            'success',
            'error'
        ]);

        // Assert data in DB
        $this->notseeInDatabase('profile', [
            'name' => 'john tester',
            'age' => 34,
            'biography' => 'I like to test',
            'image_url' => 's3.com/photo.jpg'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['error'];
        $this->assertEquals(current($data['image_url']), 'The image url format is invalid.');
    }

    public function testUpdateProfileSuccess()
    {
        // create mock profiles
        $profiles = Profile::factory()->count(10)->create();
        $profile = $profiles->first();

        $data = [
            'name' => 'john tester',
            'age' => 34,
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ];

        $response = $this->json('PUT', '/profiles/' . $profile->id, $data);
        // Assert Response
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'age',
                'biography',
                'image_url'
            ]
        ]);

        // Assert data in DB
        $this->seeInDatabase('profile', [
            'name' => 'john tester',
            'age' => 34,
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['data'];
        $this->assertEquals($data['id'], $profile->id);
        $this->assertEquals($data['name'], 'john tester');
        $this->assertEquals($data['age'], 34);
        $this->assertEquals($data['biography'], 'I like to test');
        $this->assertEquals($data['image_url'], 'http://s3.com/photo.jpg');
    }

    public function testUpdateProfileWithStringInAgeFailed()
    {
        // create mock profiles
        $profiles = Profile::factory()->count(10)->create();
        $profile = $profiles->first();

        $data = [
            'name' => 'john tester',
            'age' => 'hello',
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ];

        $response = $this->json('PUT', '/profiles/' . $profile->id, $data);
        // Assert Response
        $response->assertResponseStatus(422);
        $response->seeJsonStructure([
            'success',
            'error'
        ]);

        // Assert data in DB
        $this->notseeInDatabase('profile', [
            'name' => 'john tester',
            'age' => 'hello',
            'biography' => 'I like to test',
            'image_url' => 'http://s3.com/photo.jpg'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['error'];
        $this->assertEquals(current($data['age']), 'The age must be an integer.');
    }

    public function testUpdateProfileWithInvalidImageUrlFailed()
    {
        // create mock profiles
        $profiles = Profile::factory()->count(10)->create();
        $profile = $profiles->first();

        $data = [
            'name' => 'john tester',
            'age' => 34,
            'biography' => 'I like to test',
            'image_url' => 's3.com/photo.jpg'
        ];

        $response = $this->json('PUT', '/profiles/' . $profile->id, $data);
        // Assert Response
        $response->assertResponseStatus(422);
        $response->seeJsonStructure([
            'success',
            'error'
        ]);

        // Assert data in DB
        $this->notseeInDatabase('profile', [
            'name' => 'john tester',
            'age' => 34,
            'biography' => 'I like to test',
            'image_url' => 's3.com/photo.jpg'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['error'];
        $this->assertEquals(current($data['image_url']), 'The image url format is invalid.');
    }

    public function testUpdateProfileDoesNotExistsFailed()
    {
        $data = [
            'name' => 'john tester',
            'age' => 34,
            'biography' => 'I like to test',
        ];

        $response = $this->json('PUT', '/profiles/1', $data);
        // Assert Response
        $response->assertResponseStatus(404);
        $response->seeJsonStructure([
            'success',
            'error'
        ]);

        // Assert data in DB
        $this->notseeInDatabase('profile', [
            'name' => 'john tester',
            'age' => 34,
            'biography' => 'I like to test',
            'image_url' => 's3.com/photo.jpg'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertEquals($data['error'], 'The profile you are trying to update, does not exist.');
    }

    public function testDeleteProfileSuccess()
    {
        // create mock profiles
        $profiles = Profile::factory()->count(10)->create();
        $profile = $profiles->first();

        $response = $this->delete('/profiles/' . $profile->id);

        // Assert Response
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'age',
                'biography',
                'image_url'
            ]
        ]);
        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $data = $data['data'];
        $this->assertNotEmpty($data['deleted_at']);
    }

    public function testDeleteProfileDoesNotExistsFailed()
    {
        $response = $this->delete('/profiles/1');

        // Assert Response
        $response->assertResponseStatus(404);
        $response->seeJsonStructure([
            'success',
            'error'
        ]);

        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertEquals($data['error'], 'The profile you are trying to delete, does not exist.');
    }

    public function testGetProfileAverageAgeSuccess()
    {
        // Create mock profiles
        $ageSum = 0;
        $profiles = Profile::factory()->count(10)->create();
        // Get Avg Age
        foreach ($profiles as $profiles) {
            $ageSum += $profiles->age;
        }
        $averageAge = $ageSum / $profiles->count();
        $response = $this->get('/profiles/average/age');
        $response->seeJsonStructure([
            'success',
            'data'
        ]);
        $data = json_decode($response->response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertEquals($data['data'], $averageAge);
    }
}
