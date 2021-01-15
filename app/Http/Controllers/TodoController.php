<?php

namespace App\Http\Controllers;

use App\Todo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public $users;

    public function __construct()
    {
        $this->users = User::getAllUsers();
    }

    /** 
     *  assigner une todo a un user
     * @param App\User $user
     * @param App\Todo $todo
     *@return \Illuminate\Http\Response
     */ 
    public function affectedto(Todo $todo, User $user){
        $todo->affectedTo_id = $user->id;
        $todo->affectedBy_id = Auth::user()->id; //user actuellement connecte
        $todo->update();

        $user->notify(new TodoAffected($todo));

        return back();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId= Auth::user()->id;
        $datas = Todo::where(['affectedTo_id' => $userId])->orderBy('id','desc')->paginate(10);
        // $datas = Todo::all()->reject(function($todo){
        //     return $todo->done == 0;
        // });
        $users = $this->users;
        return view('todos.index', compact('datas', 'users'));
    }

    // liste des todos done
    public function done()
    {
        $datas = Todo::where('done', 1)->paginate(10);
        $users = $this->users;
        return view('todos.index', compact('datas', 'users'));
    }
     // liste des todos undone
     public function undone()
     {
         $datas = Todo::where('done', 0)->paginate(10);
         $users = $this->users;
         return view('todos.index', compact('datas', 'users'));
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // ajouter une todo
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //creation du todo
        $todo = new Todo();
        $todo->creator_id = Auth::user()->id;
        $todo->affectedTo_id = Auth::user()->id;
        $todo->name = $request->name ;
        $todo->description = $request->description;
        $todo->save();

        notify()->success ("la todo <span class='badge badge-dark'>#$todo->id</span> vient d'etre créée");
         return redirect()->route('todos.index');
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
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        if(!isset($request->done)){
            $request['done'] = 0;
        }
        $todo->update($request->all());
        notify()->success ("la todo <span class='badge badge-dark'>#$todo->id</span> vient d'etre mise à jour");
        return redirect()->route('todos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        notify()->error ("la todo <span class='badge badge-dark'>#$todo->id</span> vient d'etre supprimée");
        return back();
    }

    // fontion qui prend en parametre une todo done(1) et le passe en undone(0)
    public function makedone( Todo $todo){
        $todo->done = 1;
        $todo->update();
        notify()->success ("la todo <span class='badge badge-dark'>#$todo->id</span> vient d'etre terminée");
        return back();
    }

    // fontion qui prend en parametre une todo undone(0) et le passe en done(1)
    public function makeundone( Todo $todo){
        $todo->done = 0;
        $todo->update();
        notify()->success ("la todo <span class='badge badge-dark'>#$todo->id</span> vient est ouverte");
        return back();
    }
}
