<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Judges';

        $data['records'] = User::where('role','<>','admin')->get();

        return view('admin.judges.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = 'Create New Judge';

        return view('admin.judges.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|max:12',
        ]);

        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'judge'
        ]);

        if($data) {
            session()->flash('success','New Judge has been added successfully!');
            return redirect()->route('judges.index');
        } else {
            session()->flash('error','Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['title'] = 'Edit Judge';

        $data['judge'] = User::find($id);

        return view('admin.judges.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            // 'password' => 'min:6|max:12',
        ]);

        $items = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'judge',];

        if($request->password <> "") {
            $items['password'] = Hash::make($request->password);
        }

        // dd($data);

        $data = User::findOrFail($id);
        $data->update($items);

        // dd($data);

        if($data) {
            session()->flash('success','Judge Data has been updated successfully!');
            return redirect()->route('judges.index');
        } else {
            session()->flash('error','Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
