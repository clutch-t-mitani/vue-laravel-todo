<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import axios from 'axios'

const props = defineProps({
    todos: Array
})

const form = useForm({
    title: '',

})

const storeTodo = () => {
    form.post(route('todo.store'), {
        onSuccess: () => form.reset(),
    });

}

// 完了済みタスク件数
// const completed_task_count = computed(() => {
//   console.log('aaaa')
//   return props.todos?.filter(todo => todo.is_completed).length ?? 0
// })

// // 未完了タスク件数
// const uncompleted_task_count = computed(() => {
//   console.log('iiii')
//   return props.todos?.filter(todo => !todo.is_completed).length ?? 0
// })

// const task_counts = computed(() => {
//   let completed = props.todos.filter(todo => todo.is_completed).length;
//   let uncompleted = props.todos.filter(todo => !todo.is_completed).length;
//   return { completed, uncompleted };
// });

const toggleTodo = async (todo) => {
  todo.is_completed = !todo.is_completed;

  try {
    const response = await axios.patch(`/api/todos/${todo.id}`);
    console.log(' 成功:', response.data);
  } catch (error) {
    console.error('エラー:', error.response ? error.response.data : error);
    // エラーが出たら元に戻す（オプション）
    todo.is_completed = !todo.is_completed;
    }
};

// 完了済みのタスクを削除
const deleteTodo = () => {
	console.log('adafa')
	form.delete(route('todo.destroy', {todos: props.todos.filter(todo => todo.is_completed)}), {
			onBefore: () => confirm('本当に削除しますか?')
	})
}

</script>

<template>
<div class="p-2">
    <h1 class="text-2xl p-2">TODO一覧</h1>
    <form @submit.prevent="storeTodo">
        <input type="text" v-model="form.title" class="mx-2 bg-gray-200">
        <PrimaryButton class="mt-1">送信</PrimaryButton>
        <InputError class="mt-2 flex" :message="form.errors.title" />
    </form>
    <br>
    <p>未対応: {{ props.todos.filter(todo => !todo.is_completed).length }}件</p>
    <p>対応済み: {{ props.todos.filter(todo => todo.is_completed).length }} 件</p>
    <br>
    <form @submit.prevent="deleteTodo">
        <ul class="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400">
            <li v-for="todo in todos" :key="todo.id" class="flex items-center cursor-pointer" @click="toggleTodo(todo)" >
                <Checkbox
                    :checked="todo.is_completed ? true : false"
                    :value="todo.id"
                    class="mr-2 bg-gray-200"
                />
                <span :class="{ 'line-through': todo.is_completed }">
                    {{ todo.title }}
                </span>
            </li>
        </ul>
        <DangerButton
            class="mt-2 ml-1"
            :disabled="props.todos.filter(todo => todo.is_completed).length > 0 ? false : true"
            :class="{ '!bg-red-200': props.todos.filter(todo => todo.is_completed).length == 0 }"
            >完了済タスクを削除
            </DangerButton>
    </form>
</div>
</template>
