<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Contact::search($search);

        if (!empty($search)) {
            $searchLower = strtolower($search);
            $query->orderByRaw("
                CASE
                    WHEN LOWER(name) = ? THEN 1
                    WHEN LOWER(email) = ? THEN 1
                    WHEN phone = ? THEN 1
                    WHEN LOWER(name) LIKE ? THEN 2
                    WHEN LOWER(email) LIKE ? THEN 2
                    WHEN phone LIKE ? THEN 2
                    ELSE 3
                END
            ", [
                $searchLower,         // Exact name match
                $searchLower,         // Exact email match
                $search,              // Exact phone match
                $searchLower . '%',   // Starts with name
                $searchLower . '%',   // Starts with email
                $search . '%',        // Starts with phone
            ]);
        }

        $contacts = $query->paginate(10);
        return ContactResource::collection($contacts);
    }

    public function store(Request $request)
    {
        $contact = Contact::create($request->all());
        return new ContactResource($contact);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
        return new ContactResource($contact);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->noContent();
    }
}
