<template>
  <div>
    <hr>
    <div>
      <form @submit.prevent="onSubmitAnimalName">
        <div>
          <label for="name">ANIMAL NAME</label>
          <input type="text" v-model="animalNameValue" class="mx-2 border border-gray-300 rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
        </div>
        <PrimaryButton type="submit">Submit</PrimaryButton>
      </form>
      <p v-for="animal in animals" :key="animal.id">
        {{ animal.name }}
      </p>
    </div>
    <br>
    <hr>
    <!-- <div>
      <form @submit.prevent="onSubmitVegetableName">
        <div>
          <label for="name">VEGETABLE NAME</label>
          <input type="text" v-model="vegetableNameValue" />
        </div>
        <button type="submit">Submit</button>
      </form>
      <p v-for="vegetable in vegetables" :key="vegetable.id">
        {{ vegetable.name }}
      </p>
    </div>-->
  </div> 
</template>
<script setup>
  import usePageAnimal from '@/Components/Composables/usePageAnimal'
  import PrimaryButton from '@/Components/PrimaryButton.vue'

  const { animals, animalNameValue, submitAnimalName } = usePageAnimal();

  console.log(animals)


  const onSubmitAnimalName = () => {
    submitAnimalName();
  };

  // const { vegetables, vegetableNameValue, submitVegetableName } = usePageVegetable();
  // const onSubmitVegetableName = () => {
  //   submitVegetableName();
  // };
</script>