<?php

namespace Tests\Unit;

use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Validator;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_can_be_created()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890'
        ];

        $contact = Contact::create($contactData);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals($contactData['name'], $contact->name);
        $this->assertEquals($contactData['email'], $contact->email);
        $this->assertEquals($contactData['phone'], $contact->phone);
    }

    public function test_contact_can_be_updated()
    {
        $contact = Contact::factory()->create();

        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+0987654321'
        ];

        $contact->update($updatedData);
        $contact->refresh();

        $this->assertEquals($updatedData['name'], $contact->name);
        $this->assertEquals($updatedData['email'], $contact->email);
        $this->assertEquals($updatedData['phone'], $contact->phone);
    }

    public function test_contact_can_be_deleted()
    {
        $contact = Contact::factory()->create();

        $contactId = $contact->id;
        $contact->delete();

        $this->assertNull(Contact::find($contactId));
    }

    public function test_contact_requires_name()
    {
        $data = [
            'email' => 'test@example.com',
            'phone' => '+1234567890'
        ];

        $validator = Validator::make($data, (new ContactRequest())->rules());
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('name'));
    }

    public function test_contact_requires_email()
    {
        $data = [
            'name' => 'Test User',
            'phone' => '+1234567890'
        ];

        $validator = Validator::make($data, (new ContactRequest())->rules());
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
    }

    public function test_contact_requires_phone()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ];

        $validator = Validator::make($data, (new ContactRequest())->rules());
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('phone'));
    }

    public function test_contact_email_must_be_valid()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'not-an-email',
            'phone' => '+1234567890'
        ];

        $validator = Validator::make($data, (new ContactRequest())->rules());
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
    }

    public function test_contact_phone_must_be_valid()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '123' // too short
        ];

        $validator = Validator::make($data, (new ContactRequest())->rules());
        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('phone'));
    }
}
