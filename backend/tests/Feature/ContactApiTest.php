<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_can_get_contacts_list()
    {
        Contact::factory()->count(3)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data')
                ->assertJsonStructure([
                    'data' => [[
                        'id',
                        'name',
                        'email',
                        'phone'
                    ]],
                    'meta' => [
                        'current_page',
                        'last_page',
                        'total'
                    ]
                ]);
    }

    public function test_can_create_contact()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890'
        ];

        $response = $this->postJson('/api/contacts', $contactData);

        $response->assertStatus(201)
                ->assertJson([
                    'data' => [
                        'name' => $contactData['name'],
                        'email' => $contactData['email'],
                        'phone' => $contactData['phone']
                    ]
                ]);

        $this->assertDatabaseHas('contacts', $contactData);
    }

    public function test_can_update_contact()
    {
        $contact = Contact::factory()->create();

        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+0987654321'
        ];

        $response = $this->putJson("/api/contacts/{$contact->id}", $updatedData);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => $updatedData
                ]);

        $this->assertDatabaseHas('contacts', $updatedData);
    }

    public function test_can_delete_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_cannot_create_contact_with_invalid_data()
    {
        $invalidData = [
            'name' => '', // empty name
            'email' => 'not-an-email',
            'phone' => '123' // too short
        ];

        $response = $this->postJson('/api/contacts', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'phone']);
    }

    public function test_cannot_access_contacts_without_authentication()
    {
        // Create a new instance without authentication
        $this->app->get('auth')->forgetGuards();

        $response = $this->getJson('/api/contacts');
        $response->assertStatus(401);
    }

    public function test_can_search_contacts()
    {
        Contact::factory()->create(['name' => 'John Doe']);
        Contact::factory()->create(['name' => 'Jane Smith']);
        Contact::factory()->create(['email' => 'john@example.com']);

        $response = $this->getJson('/api/contacts?search=john');

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
    }

    public function test_contacts_are_paginated()
    {
        Contact::factory()->count(15)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200)
                ->assertJsonCount(10, 'data') // Default pagination is 10
                ->assertJsonStructure([
                    'meta' => [
                        'current_page',
                        'last_page',
                        'total'
                    ]
                ]);
    }
}
