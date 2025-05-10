import { computed, ref } from "vue";

export default function usePageAnimal() {
  const animals = ref([]);
  const animalNameValue = ref("");
  const animalId = ref(0);

  const submitAnimalName = () => {
    animals.value.push({
      id: animalId.value,
      name: animalNameValue.value,
    });
    animalNameValue.value = "";
    animalId.value = animalId.value + 1;
  };

  return {
    // animals: computed(() => animals.value),
    animals,
    animalNameValue,
    submitAnimalName,
  };
}
