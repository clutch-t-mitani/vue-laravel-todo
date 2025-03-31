<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Todo::truncate();

        $todos = ['猫に餌を上げる', '洗濯', 'サッカー', '宿題'];

        foreach ($todos as $todo_name) {
            Todo::create([
                'title' => $todo_name
            ]);
        }
    }
}
