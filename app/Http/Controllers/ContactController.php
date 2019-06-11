<?php

namespace App\Http\Controllers;

use App\Contact;
use App\User;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use JeroenDesloovere\VCard\VCard;
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
            $data['users'] = User::whereNotNull('email_verified_at')->where('id', '!=', Auth::user()->id)->get();
            return view('contacts.listing', $data);
        } catch (\Exception $ex) {
            return abort('404');
        }
    }

    public function getContacts($shared = false)
    {
        try {
            $shared_contacts = DB::table('contact_user')->where('user_id', Auth::user()->id)->get()->toArray();
            $contacts = ($shared) ? Contact::whereIn('id',  array_column($shared_contacts, 'contact_id')) : Contact::where('created_by', Auth::user()->id);
            return DataTables::of($contacts)
                ->addColumn('checkbox', function ($m) {
                    return $m->id;
                })
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
                ->addColumn('action', function ($m) use ($shared) {
                    $responseBody['id'] = $m->id;
                    $responseBody['shared'] = $shared;
                    return view('contacts.listing-actions')->with($responseBody);
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $sql = "CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('contacts', function ($query, $keyword) {
                    $sql = "primary_phone LIKE ? OR secondary_phone LIKE ?";
                    $query->whereRaw($sql, ["{$keyword}%", "{$keyword}%"]);
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
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'alpha', 'max:255'],
                'middle_name' => ['nullable', 'alpha', 'max:255'],
                'last_name' => ['required', 'alpha', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'primary_phone' => ['required', 'numeric'],
                'secondary_phone' => ['nullable', 'numeric']
            ]);

            if ($validator->fails()) {
                return redirect()->route('contacts.create', $request->get('_id'))
                    ->withErrors($validator)
                    ->withInput();
            }

            request()->request->add(['created_by' => Auth::user()->id]);

            if (!is_null($request->photo))
                request()->request->add(['photo' => $this->saveImage($request->photo)]);

            Contact::create($request->all());

            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Contact Added Successfully');

            return redirect('/contacts');
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . 'on line no. ' . $ex->getLine());
        }
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
            Log::info($ex->getMessage() . 'on line no. ' . $ex->getLine());
            return false;
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
            return view('contacts.edit')->withContact($contact);
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
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'alpha', 'max:255'],
                'middle_name' => ['nullable', 'alpha', 'max:255'],
                'last_name' => ['required', 'alpha', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'primary_phone' => ['required', 'numeric'],
                'secondary_phone' => ['nullable', 'numeric']
            ]);

            if ($validator->fails()) {
                return redirect()->route('contacts.edit', $request->get('_id'))
                    ->withErrors($validator)
                    ->withInput();
            }

            $contact = Contact::find($request->get('_id'));
            $contact->first_name = $request->first_name;
            $contact->middle_name = $request->middle_name;
            $contact->last_name = $request->last_name;
            $contact->email = $request->email;
            $contact->primary_phone = $request->primary_phone;
            $contact->secondary_phone = $request->secondary_phone;

            if (!is_null($request->photo))
                $contact->photo = $this->saveImage($request->photo);

            $contact->save();

            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Contact Updated Successfully');

            return redirect('/contacts');
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . 'on line no. ' . $ex->getLine());
        }
    }

    /**
     * Share the specified contact in storage with specified User.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function share(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $contact = Contact::find($request->contact_id);
            $user->contacts()->attach($contact->id);

            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Contact Shared Successfully');

            return redirect('/contacts');
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . 'on line no. ' . $ex->getLine());
        }
    }

    /**
     * Export the specified contact as a vcf
     *
     * @param  \App\Contact $contact
     * @return \JeroenDesloovere\VCard\
     */
    public function show(Contact $contact)
    {
        $vcard = new VCard();
        $vcard->addName($contact->last_name, $contact->first_name, $contact->middle_name);
        $vcard->addEmail($contact->email);
        $vcard->addPhoneNumber($contact->primary_phone, 'PREF;WORK');
        $vcard->addPhoneNumber($contact->secondary_phone, 'WORK');
        $vcard->addPhoto(public_path() . $contact->photo);
        return $vcard->download();
    }

    public function export(Request $request)
    {
        try {
            if ($request->contacts) {
                $contacts = Contact::find(explode(',', $request->contacts));
                $time = time();
                $directory = '/vcards/' . $time . '/';
                $path = public_path() . $directory;
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, $recursive = true, $force = false);
                }
                foreach ($contacts as $contact) {
                    $vcard = new VCard();
                    $vcard->addName($contact->last_name, $contact->first_name, $contact->middle_name);
                    $vcard->addEmail($contact->email);
                    $vcard->addPhoneNumber($contact->primary_phone, 'PREF;WORK');
                    $vcard->addPhoneNumber($contact->secondary_phone, 'WORK');
                    $vcard->addPhoto(public_path() . $contact->photo);
                    $vcard->setSavePath($path);
                    $vcard->save();
                }

                $files = glob(public_path($directory . '/*'));
                Zipper::make(public_path($time . '.zip'))->add($files)->close();
                File::deleteDirectory($path);
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', 'Contacts Exported Successfully');

                return response()->download(public_path($time . '.zip'));
            }
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', 'Please Select Contacts to Export');

            return redirect('/contacts');
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . 'on line no. ' . $ex->getLine());
        }
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
            File::delete(public_path() . $contact->photo);
            $contact->delete();
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Contact Deleted Successfully');

            return redirect('/contacts');

        } catch (\Exception $ex) {
            return abort('404');
        }
    }
}
