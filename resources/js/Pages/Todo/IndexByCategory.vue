<script setup>
import draggable from 'vuedraggable'

const props = defineProps({
  todos_by_category: Object,
  category_list: Object
})

const onTodoMoved = async (newCategoryId, event) => {
  const movedTodo = event?.added?.element;
  if (!movedTodo) return;
  const originalCategory = movedTodo.category; // ← 元のカテゴリを保持

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
};

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
              <v-card-title>
                {{ category_name }}
              </v-card-title>

              <!-- draggable をここで使用 -->
              <draggable
                v-model="todos_by_category[category_id]"
                :group="{ name: 'todos', pull: true, put: true }"
                item-key="id"
                @change="(event) => onTodoMoved(category_id, event)"
              >
                  <template #item="{ element: todo }">
                    <v-card-subtitle class="cursor-pointer">
                      ・{{ todo.title }}
                    </v-card-subtitle>
                  </template>
              </draggable>
            </v-card-item>
          </v-card>
        </v-col>
      </v-row>
    </v-app>
  </div>
</template>
