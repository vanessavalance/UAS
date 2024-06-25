<?php

namespace App\Http\Controllers;
use App\Models\Contact;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class ContactsController extends Controller
{

    public function get($id): JsonResponse
    {
        $contact = Contact::find($id);
        if (!$contact){
            return response()->json([
                'message' => 'Contact not found'
            ]);
        }
        return response()->json([
            "data" => [
                'id'=> $contact->id,
                'name'=> $contact->name,
                'email'=> $contact->email,
                'phone'=> $contact->phone,
                'street'=> $contact->street,
                'city'=> $contact->city,
                'state'=> $contact->state,
                'zip'=> $contact->zip,
                'type'=> $contact->type,
                'users'=> $contact->user,
                'childs'=> $contact->childs,
            ]
        ])->setStatusCode(200);

    }
}