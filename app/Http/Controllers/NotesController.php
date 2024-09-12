<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;


class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $notes = Note::query()
            ->where('user_id', request()->user()->id)
            ->orderBy("created_at","desc")
            ->paginate();
        return view("note.index", ['notes' => $notes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("note.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'note' =>   ['required', 'string ',],
             
        ]);
        
        $data['user_id'] = $request->user()->id; 
        $note = Note::create($data);
        return to_route('note.show', $note)->with('message', 'Note Was created');
 
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        if ($note->user_id !== request()->user()-> id){
            abort(404);
        }
        return view("note.show", ['note' => $note]);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
        return view("note.edit", ['note' => $note]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        //
        $data = $request->validate([
            'note' =>   ['required', 'string ',],
             
        ]);
        
       
        $note->update($data);
        return to_route(route: 'note.show', parameters: $note)->with(key: 'message', value: 'Message was updated ');

    }

    /**
     * Remove the specified resource from storage.
    */
    public function destroy(Note $note)
    {
        //
        $note->delete();
        return to_route(route:'note.index')->with('message', "Note was deleted");
         
    }
}
