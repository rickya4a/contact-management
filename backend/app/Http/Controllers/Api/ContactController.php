<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Resources\ContactResource;

class ContactController extends Controller
{
    public function index()
    {
        return ContactResource::collection(Contact::paginate(10));
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
