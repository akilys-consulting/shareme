//
// MODULE DE GESTION DE LA CONNEXION
//    - gestion de la connexion
//    - gestion du prodil
//
//

// import the module instance
import { ref, computed } from 'vue';
import { getCookieUser } from 'src/utils/cookie';
import { type UserType } from 'src/types/users';

import { defineStore } from 'pinia';

export const userStore = defineStore('connexion', () => {
  const connected = ref(false);
  const userConnected = ref<UserType>();
  //
  // vrai si utilisateur à un token

  const getIsConnected = computed(() => {
    return connected.value;
  });
  //
  // récupération de l'utilisateur connecté
  const getUserConnected = computed(() => {
    return userConnected;
  });

  function refreshConnected() {
    connected.value = getCookieUser().token ? true : false;
  }

  //
  // mise à jour de l'utilisateur connecté
  function setUserConnected() {
    userConnected.value = getCookieUser();
  }
  return {
    connected,
    refreshConnected,
    setUserConnected,
    getIsConnected,
    getUserConnected,
  };
});
