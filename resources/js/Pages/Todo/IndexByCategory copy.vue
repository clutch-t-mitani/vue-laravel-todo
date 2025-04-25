<script setup>
  const props = defineProps({
    todos_by_category: Object,
    category_list: Object
  })
</script>

<template>
  <div class="p-10">
    <v-app>
      <v-row>  
        <v-col 
          cols="12" 
          :md="12 / Object.keys(todos_by_category).length"
          v-for="(todos, index) in todos_by_category"
          >
          <v-card>
            <v-card-item  >
              <v-card-title>
                {{ category_list[index] }}
              </v-card-title>
    
              <v-card-subtitle 
              v-for="(todo, index) in todos"
              :key="todo.id"
              >
              ・{{ todo.title }}
              </v-card-subtitle>
            </v-card-item>
          </v-card>
        </v-col>
      </v-row>
    </v-app>
  </div>
</template>