import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { EvenementType } from 'src/types/evenements';

export const evtStore = defineStore('evt', () => {
  const currentEvt = ref<EvenementType>();

  const getCurrentEvt = computed(() => currentEvt.value);

  function setCurrentEvt(data: EvenementType) {
    currentEvt.value = data;
  }

  return {
    getCurrentEvt,
    setCurrentEvt,
  };
});
