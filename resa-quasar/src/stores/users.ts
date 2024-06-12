//
// MODULE DE GESTION DE LA CONNEXION
//    - gestion de la connexion
//    - gestion du prodil
//
//

// import the module instance
import { ref, computed } from 'vue';
import { getTokenCnx } from 'src/utils/cookie';

import { defineStore } from 'pinia';

export const userStore = defineStore('connexion', () => {
  const connected = ref(false);
  //
  // vrai si utilisateur à un token

  const getIsConnected = computed(() => {
    return connected.value;
  });

  function refreshConnected() {
    connected.value = getTokenCnx() ? true : false;
  }

  return {
    connected,
    refreshConnected,
    getIsConnected,
  };
});
