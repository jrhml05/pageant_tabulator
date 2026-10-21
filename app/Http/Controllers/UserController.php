<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $data['title'] = 'Judges';
        $data['panels'] = collect(config('pageant.panels'))->map(fn ($label, $panel) => User::onPanel($panel)->get());
        $data['unassigned'] = User::where('role', 'judge')->whereNull('panel')->orderBy('id')->get();

        return view('admin.judges.index', compact('data'));
    }

    public function create()
    {
        $data['title'] = 'Add judge';

        return view('admin.judges.create', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'panel' => $validated['panel'],
            'password' => Hash::make($validated['password']),
            'role' => 'judge',
        ]);

        session()->flash('success', "{$validated['name']} added. They sign in as {$validated['username']}.");

        return redirect()->route('judges.index');
    }

    public function edit(int $id)
    {
        $data['title'] = 'Edit judge';
        $data['judge'] = User::where('role', 'judge')->findOrFail($id);

        return view('admin.judges.edit', compact('data'));
    }

    public function update(Request $request, int $id)
    {
        $judge = User::where('role', 'judge')->findOrFail($id);
        $validated = $this->validated($request, $judge);

        $judge->fill([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'panel' => $validated['panel'],
        ]);
        if (filled($validated['password'] ?? null)) {
            $judge->password = Hash::make($validated['password']);
        }
        $judge->save();

        session()->flash('success', "{$judge->name} saved.");

        return redirect()->route('judges.index');
    }

    private function validated(Request $request, ?User $judge = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9._-]+$/', Rule::unique('users')->ignore($judge)],
            'panel' => ['required', Rule::in(array_keys(config('pageant.panels')))],
            'password' => [$judge ? 'nullable' : 'required', 'string', 'min:6', 'max:64'],
        ], [
            'username.regex' => 'Use letters, numbers, dots, dashes or underscores only, with no spaces.',
            'username.unique' => 'Another account already uses this username.',
            'panel.required' => 'Pick the panel this judge sits on.',
        ]);
    }
}
