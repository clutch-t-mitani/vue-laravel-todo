<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::select('id', 'title', 'is_completed', 'category')->get();
        $category_list = Todo::CATEGORY_LIST;

        return Inertia::render('Todo/Index', [
            'todos' => $todos,
            'category_list' => $category_list,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexByCategory()
    {
        $todos_by_category = Todo::select('id', 'title', 'is_completed', 'category')->get()->groupBy('category');
        $category_list = Todo::CATEGORY_LIST;
        return Inertia::render('Todo/IndexByCategory', [
            'todos_by_category' => $todos_by_category,
            'category_list' => $category_list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        Todo::create($request->all());
        return redirect()->route('todo.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, int $id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update([
            'is_completed' => !$todo->is_completed
        ]);

        return response()->json([
            'message' => '更新しました',
            'todo' => $todo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = collect($request->todos)->pluck('id');
        Todo::destroy($ids);

        return to_route('todo.index')->with([
            // 'message' => '登録しました',
            // 'status' => 'success',
        ]);
    }
}
