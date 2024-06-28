import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { setFilterEVt, getFilterEVt } from 'src/utils/cookie';

import { i18n } from 'src/boot/i18n';
import { Notify, Loading, QSpinnerGrid } from 'quasar';
import { type filterEventType } from 'src/types/evenements';

interface messageType {
  display?: boolean;
  code: string;
  param?: string;
  type?: string;
}

export const ihmStore = defineStore('ihm', () => {
  const displayMenu = ref(false);
  //
  // affichage d'un spinner
  const waiting = ref(false);
  // indique si l'écran en cours a été modifié
  const modificationEnCours = ref(false);
  // gestion de l'affichage des messages
  const message = ref({
    display: false,
    code: 'Oups, je vais disparaitre',
    param: 'PARAM',
    type: 'success',
  });

  const getWaiting = computed(() => waiting.value);
  const getMessage = computed(() => message.value);
  const getDisplayMenu = computed(() => displayMenu.value);

  /*
// gestion du filtre sur évènements
*/

  // mémorisation du filtre
  const dataFilter = ref<filterEventType>();
  const displayFilter = ref(false);

  // mémorisation d'un filtre sur la date
  function setDataFilter(data: filterEventType) {
    dataFilter.value = data;
    setFilterEVt(data);
  }

  //
  // récupération du filtre mémorisé
  const getDataFilter = computed(() => {
    if (dataFilter.value == null) dataFilter.value = getFilterEVt();
    return dataFilter.value;
  });

  function displayRecherche() {
    displayFilter.value = !displayFilter.value;
  }

  function addStringSearch(value: string) {
    if (dataFilter.value) {
      dataFilter.value.search = value;
      setFilterEVt(dataFilter.value);
    }
  }

  function filteractif() {
    const data = getFilterEVt();
    console.log('cat', data);
    return data.cat.length != 0 ? true : false;
  }

  function setDisplayMenuOff() {
    displayMenu.value = false;
  }

  function setDisplayMenuOn() {
    displayMenu.value = true;
  }

  function setMessage(messageData: messageType) {
    message.value.param = messageData.param ? messageData.param : '';
    message.value.code = messageData.code;
  }

  function setDisplayMessage() {
    message.value.display = true;
  }

  function displayInfo(messageData: messageType) {
    setMessage(messageData);
    Notify.create({
      type: 'positive',
      color: 'blue-grey-3',
      message: i18n.global.t('message.' + message.value.code, {
        PARAM: message.value.param,
      }),
      position: 'top',
      actions: [{ icon: 'close', color: 'white', round: true }],
    });
  }
  function displayError(messageData: messageType) {
    setMessage(messageData);
    Notify.create({
      type: 'negative',
      color: 'red-7',
      message: i18n.global.t('message.' + message.value.code, {
        PARAM: message.value.param,
      }),
      position: 'top',
      actions: [{ icon: 'close', color: 'white', round: true }],
    });
  }

  function startWaiting() {
    Loading.show({
      spinner: QSpinnerGrid,
      spinnerColor: 'red-10',
      spinnerSize: 80,
      backgroundColor: 'blue-grey-4',
      message: 'Action en cours...',
      messageColor: '#a7293e',
    });
  }

  function stopWaiting() {
    Loading.hide();
  }

  return {
    displayFilter,
    dataFilter,
    message,
    getWaiting,
    getMessage,
    getDisplayMenu,
    setMessage,
    startWaiting,
    stopWaiting,
    setDisplayMenuOff,
    setDisplayMenuOn,
    setDisplayMessage,
    displayInfo,
    displayError,
    filteractif,
    setDataFilter,
    getDataFilter,
    displayRecherche,
    addStringSearch,
  };
});
