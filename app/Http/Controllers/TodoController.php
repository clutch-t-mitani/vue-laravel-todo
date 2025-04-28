<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::select('id', 'title', 'is_completed', 'category', 'created_at')->get();
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
        $todos_grouped = Todo::select('id', 'title', 'is_completed', 'category')->get()->groupBy('category');
        $category_list = Todo::CATEGORY_LIST;
        
        $todos_by_category = [];

        foreach ($category_list as $id => $category_name) {
            $todos_by_category[$id] = $todos_grouped->get($id, collect())->values();
        }
        
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
        session()->flash('success', 'ユーザー登録しました。');
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
     * カテゴリ変更
     * @param $request
     * @param $todo_id
     * @return json
     */
    public function updateCategory(Request $request, $todo_id)
    {
        // バリデーション（任意）
        $validated = $request->validate([
            'category' =>  ['required', Rule::in(array_keys(Todo::CATEGORY_LIST))],
        ]);

        $todo = Todo::find($todo_id);

        // 更新処理
        $todo->category = $validated['category'];
        $todo->save();

        return response()->json([
            'message' => 'カテゴリ更新成功',
            'todo' => $todo,
        ]);
    }

    /**
     * カテゴリ変更
     * @param $request
     * @param $todo_id
     * @return json
     */
    public function updateTitle(Request $request, $todo_id)
    {
        $todo = Todo::find($todo_id);
        $todo->update([
            'title' => $request->title
        ]);

        return redirect()->route('todo.index-by-category');
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
