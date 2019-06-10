<?php

namespace App\Http\Controllers;

use App\Contact;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    /**
     * Display a listing of the contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return view('contacts.listing');
        } catch (\Exception $ex) {
            return abort('404');
        }
    }

    public function getContacts()
    {
        try {
            $users = DB::table('contacts')->where('created_by', Auth::user()->id);
            $responseBody = array();
            return DataTables::of($users)
                ->addColumn('name', function ($m) {
                    $name = $m->first_name . " " . $m->middle_name . " " . $m->last_name;
                    return preg_replace('!\s+!', ' ', $name);
                })
                ->addColumn('contacts', function ($m) {
                    $contacts = $m->primary_phone . ", " . $m->secondary_phone;
                    return rtrim($contacts, ', ');
                })
                ->addColumn('photo', function ($m) {
                    return (!is_null($m->photo)) ? $m->photo : 'http://daivadnyasamajgoa.org/wp-content/uploads/2017/08/icon-user-default-150x150.png';
                })
                ->addColumn('action', function ($m) {
                    $responseBody['id'] = $m->id;
                    return view('contacts.listing-actions')->with($responseBody);
                })
                ->make();
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . 'on line no. ' . $ex->getLine());
        }
    }

    /**
     * Show the form for creating a new contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('contacts.add');
        } catch (\Exception $ex) {
            return abort('404');
        }
    }

    /**
     * Store a newly created contact in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => ['required', 'max:255'],
            'middle_name' => ['max:255'],
            'last_name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'primary_phone' => ['required', 'max:15'],
            'secondary_phone' => ['max:15']
        ]);

        $validatedData['created_by'] = Auth::user()->id;

        if(!is_null($request->photo))
            $validatedData['photo'] = $this->saveImage($request->photo);

        $contact = Contact::create($validatedData);

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'Contact Added Successfully');

        return redirect('/contacts');
    }

    public function saveImage($file_data)
    {
        try {
            $file_name = 'image_' . time() . '.png';

            $directory = '/contact_images';
            $path = public_path() . $directory;
            if (!File::exists($path)) {
                File::makeDirectory($path, 0775, $recursive = true, $force = false);
            }

            if ($file_data != "") {
                File::put($path . '/' . $file_name, base64_decode($file_data));
            }
            return $directory . '/' . $file_name;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * Display the specified contact.
     *
     * @param  \App\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        try {
            return response($contact);
        } catch (\Exception $ex) {
            return abort('404');
        }
    }

    /**
     * Show the form for editing the specified contact.
     *
     * @param  \App\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        try {
            return response($contact);
        } catch (\Exception $ex) {
            return abort('404');
        }
    }

    /**
     * Update the specified contact in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        try {
            return response($contact);
        } catch (\Exception $ex) {
            return abort('404');
        }
    }

    /**
     * Share the specified contact in storage with specified User.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Contact $contact
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function share(Request $request, Contact $contact, User $user)
    {
        //
    }

    /**
     * Remove the specified contact from storage.
     *
     * @param  \App\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact, Request $request)
    {
        try {
            $contact->photo;
            File::delete(public_path() .$contact->photo);
            $contact->delete();
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Contact Deleted Successfully');

            return redirect('/contacts');

        } catch (\Exception $ex) {
            return abort('404');
        }
    }
}
