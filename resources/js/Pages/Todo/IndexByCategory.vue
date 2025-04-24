<script setup>
  const props = defineProps({
      todos: Object,
      category_list: Array
  })
</script>

<template>
  <div class="p-10">
    <v-app>
      <v-row>  
        <v-col 
          cols="12" md="6">
          <v-card>
            <v-card-item  
            v-for="(todo, index) in todos"
            :key="todo.id">
              <v-card-title>{{ todo.title }}</v-card-title>
    
              <v-card-subtitle>{{ category_list[todo.category] }}</v-card-subtitle>
            </v-card-item>
          </v-card>
    
          <div class="text-center text-caption">Using Markup Only</div>
        </v-col>
        <v-col 
          cols="12" md="6">
          <v-card>
            <v-card-item  
            v-for="(todo, index) in todos"
            :key="todo.id">
              <v-card-title>{{ todo.title }}</v-card-title>
    
              <v-card-subtitle>{{ category_list[todo.category] }}</v-card-subtitle>
            </v-card-item>
    
            <!-- <v-card-text>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus!
            </v-card-text> -->
          </v-card>
    
          <div class="text-center text-caption">Using Markup Only</div>
        </v-col>
      </v-row>
    </v-app>
  </div>
</template>