import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { EvenementType,defaultEvenement } from 'src/types/evenements';
import { saveEvenements } from 'src/api/evenement';
import { ihmStore } from 'src/stores/ihm';

const ihmModule = ihmStore();

export const evtStore = defineStore('evt', () => {
  const currentEvt = ref<EvenementType>(defaultEvenement);

  const getCurrentEvt = computed(() => currentEvt.value);
  const getCategoriesString = computed(() =>
    currentEvt.value.categories ? currentEvt.value.categories.join(', ') : ''
  );

  function setCurrentEvt(data: EvenementType) {
    currentEvt.value = data;
  }

  async function saveCurrentEvt(file: any) {
    //const data = Object.assign({}, currentEvt.value);
    const { status, message } = await saveEvenements(
      Object.assign({}, currentEvt.value),
      file
    );
    if (!status) {
      ihmModule.displayError({
        code: 'ADMIN',
        param: message,
      });
    }
    return { status: status, message: message };
  }

  return {
    getCurrentEvt,
    getCategoriesString,
    setCurrentEvt,
    saveCurrentEvt,
  };
});
