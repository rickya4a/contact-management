<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\ContactIndexRequest;
use App\Models\Contact;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index(ContactIndexRequest $request)
    {
        try {
            $contacts = Contact::search($request->get('search'))
                ->paginate($request->get('per_page', 10));
            return ContactResource::collection($contacts);
        } catch (\Exception $e) {
            Log::error('Error fetching contacts: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching contacts'], 500);
        }
    }

    public function store(ContactRequest $request)
    {
        try {
            $contact = Contact::create($request->validated());
            return new ContactResource($contact);
        } catch (\Exception $e) {
            Log::error('Error creating contact: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating contact'], 500);
        }
    }

    public function update(ContactRequest $request, $id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->update($request->validated());
            return new ContactResource($contact);
        } catch (\Exception $e) {
            Log::error('Error updating contact: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating contact'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            Log::error('Error deleting contact: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting contact'], 500);
        }
    }
}
