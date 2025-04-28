<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import axios from 'axios'
import { ref, onMounted } from 'vue';
import dayjs from 'dayjs'

const props = defineProps({
    todos: Array,
    category_list: Object,
    flash: Object
})

const showAlert = ref(false)
const alertMessage = ref('')
const alertType = ref('')

const form = useForm({
    title: '',
    category: ''
})

const storeTodo = () => {
    form.post(route('todo.store'), {
        onSuccess: () => {
            form.reset()
            alertMessage.value = '登録に成功しました'
            alertType.value = 'success'
            showTemporaryAlert()
        },
        onError: () => {
            alertMessage.value = '登録に失敗しました'
            alertType.value = 'error'
            showTemporaryAlert()
        }
    });
}

// アラートを数秒だけ表示する関数
const showTemporaryAlert = () => {
  showAlert.value = true
  setTimeout(() => {
    showAlert.value = false
  }, 3000)
}

// 完了済みタスク件数
// const completed_task_count = computed(() => {
// //   console.log('aaaa')
//   return props.flash
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
	form.delete(route('todo.destroy', {todos: props.todos.filter(todo => todo.is_completed)}), {
			onBefore: () => confirm('本当に削除しますか?')
	})
}

</script>

<template>
<div class="p-2">
    <h1 class="text-2xl p-2">TODO一覧</h1>
    <form @submit.prevent="storeTodo">
        <v-alert 
            v-if="showAlert"
            :title="alertMessage"
            :type="alertType"
            class="mb-2 w-1/2"
        ></v-alert>        
        <input type="text" v-model="form.title" class="mx-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
        <select v-model="form.category" class="pl-2 pr-8 py-2 borderborder-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition mr-2">
            <option value="" readonly>--</option>
            <option 
                v-for="(category_name, index) in category_list" 
                :key="index" 
                :value="index">{{ category_name }}
            </option>
>        </select>
        <PrimaryButton class="mt-1">送信</PrimaryButton>
        <InputError class="mt-2 flex" :message="form.errors.title" />
        <InputError class="mt-2 flex" :message="form.errors.category" />
    </form>
    <br>
    <p>未対応: {{ props.todos.filter(todo => !todo.is_completed).length }}件</p>
    <p>対応済み: {{ props.todos.filter(todo => todo.is_completed).length }} 件</p>

    <br>
    <form @submit.prevent="deleteTodo">
        <ul class="max-w-md space-y-1 text-gray-800 list-inside ">
            <li v-for="todo in todos" :key="todo.id" class="flex items-center cursor-pointer" @click="toggleTodo(todo)" >
                <Checkbox
                    :checked="todo.is_completed ? true : false"
                    :value="todo.id"
                    class="mr-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition "
                />
                <v-tooltip activator="parent" location="top">
                    作成日: {{ dayjs(todo.created_at).format('YYYY/MM/DD HH:mm') }}
                </v-tooltip>
                <span :class="{ 'line-through': todo.is_completed }">
                    {{ todo.title }} （{{ category_list[todo.category] }}）
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
