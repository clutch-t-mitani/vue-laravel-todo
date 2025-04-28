<script setup>
import { ref, watchEffect } from 'vue'
import draggable from 'vuedraggable'
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
  todos_by_category: Object,
  category_list: Object
})

const form = useForm({
    title: '',
    is_completed: '',
    category: '',

})

// モーダル制御用
const dialog = ref(false)
const selectedTodo = ref(null)

watchEffect (() => {
  if (selectedTodo.value) {
    form.title = selectedTodo.value.title
    form.category = selectedTodo.value.category
    form.is_completed = selectedTodo.value.is_completed
    form.category = selectedTodo.value.category
  }
})


const onTodoMoved = async (newCategoryId, event) => {
  const movedTodo = event?.added?.element;
  if (!movedTodo) return;
  const originalCategory = movedTodo.category;

  try {
    movedTodo.category = newCategoryId;

    const response = await axios.patch(`/api/todos/update-category/${movedTodo.id}`, {
      category: newCategoryId,
    });
    console.log('更新成功:', response.data);
  } catch (e) {
    console.error('更新失敗:', e.response?.data || e);
    movedTodo.category = originalCategory;
  }
}

// todoをクリックしたとき
const openTodoModal = (todo) => {
  selectedTodo.value = todo
  dialog.value = true
}

const updateTodo = () => {
  form.patch(route('todos.update-title', selectedTodo.value), {
    onSuccess: () => {
      form.reset();
      dialog.value = false;
    },
  });
}
</script>

<template>
  <div class="p-10">
    <v-app>
      <v-row>
        <v-col
          cols="12"
          :md="12 / Object.keys(category_list).length"
          v-for="(category_name, category_id) in category_list"
          :key="category_id"
        >
          <v-card>
            <v-card-item>
              <v-card-title>{{ category_name }}</v-card-title>

              <!-- draggable -->
              <draggable
                v-model="todos_by_category[category_id]"
                :group="{ name: 'todos', pull: true, put: true }"
                item-key="id"
                @change="(event) => onTodoMoved(category_id, event)"
              >
                <template #item="{ element: todo }">
                  <v-card-subtitle
                    class="cursor-pointer hover:bg-red-300"
                    @click="openTodoModal(todo)"
                  >
                    ・{{ todo.title }}
                  </v-card-subtitle>
                </template>
              </draggable>
            </v-card-item>
          </v-card>
        </v-col>
      </v-row>

      <!-- モーダル -->
      <v-dialog v-model="dialog" max-width="500">
        <form @submit.prevent="updateTodo">
        <v-card>
          <v-card-title>名称編集</v-card-title>
            <v-card-text>
              <div v-if="selectedTodo">
                <input type="text" v-model="form.title" class="mx-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
              </div>
            </v-card-text>
            <v-card-actions>
              <v-spacer />
              <PrimaryButton class="mt-1">送信</PrimaryButton>
              <v-btn color="primary" @click="dialog = false">閉じる</v-btn>
            </v-card-actions>
          </v-card>
        </form>
      </v-dialog>

    </v-app>
  </div>
</template>
