//
// MODULE DE GESTION DE LA CONNEXION
//    - gestion de la connexion
//    - gestion du prodil
//
//

// import the module instance

import {
  type stringNullable,
  type UserType,
  type profil,
  type PasswordType,
  defaultUser,
  K_design,
  K_client,
  K_su,
  K_commercial,
  K_admin,
  K_avartarSociete,
  K_avartarUser,
} from 'src/types/users';
import { ihmStore } from 'src/stores/ihm';
import {
  setToken,
  getToken,
  removeToken,
  getCookieUser,
  setCookieUser,
  removeCookieUser,
} from 'src/utils/cookie';

import {
  controleToken,
  getDataAvatar,
} from 'src/api/users';

import { updateMdp, setDataUser } from 'src/api/configuration';

import { ref, computed } from 'vue';
import { defineStore } from 'pinia';

export const userStore = defineStore('connexion', () => {
  const user = ref<UserType>(defaultUser);

  const ihmModule = ihmStore();

  const getSocieteId = computed((): number => {
    return user.value.societe_id;
  });

  // on retourne le nom de l'utilisateur qui est chargé
  const getNomUtilisateur = computed((): stringNullable => {
    getUser();
    return user.value.nom;
  });

  // on retourne l'id de l'utilisateur qui est chargé
  const getUserId = computed((): number | null => {
    getUser();
    return user.value.id;
  });

  const getProfil = computed(() => {
    getUser();
    return user.value.profil;
  });

  const getCategories = computed(() => {
    getUser();
    return user.value.conf.categorie;
  });

  const userIsDesign = computed((): boolean => {
    getUser();
    return user.value.profil.find((e) => e.key === K_design) ? true : false;
  });
  const userIsCommercial = computed((): boolean => {
    getUser();
    return user.value.profil.find((e) => e.key === K_commercial) ? true : false;
  });
  const userIsClient = computed((): boolean => {
    getUser();
    return user.value.profil.find((e) => e.key === K_client) ? true : false;
  });
  const isSuperUtilisateur = computed((): boolean => {
    getUser();
    return user.value.profil.find((e) => e.key === K_su) ? true : false;
  });
  const userIsAdmin = computed((): boolean => {
    getUser();
    return user.value.profil.find((e) => e.key === K_admin) ? true : false;
  });

  const userPlanAcces = computed((): boolean => {
    getUser();
    return user.value.conf.plan ? true : false;
  });

  // on récupère la data user,
  // si la data est vide on va la recgarger avec le cookie
  function getUser(): UserType {
    if (user.value.id == null) user.value = getCookieUser();
    return user.value;
  }

  //
  // vrai si utilisateur à un token
  function isAuthenticated(): boolean {
    return getToken() ? true : false;
  }

  function userCatAcces() {
    let catAcces = false;
    getUser();
    if (user.value.conf.categorie) {
      catAcces = user.value.conf.categorie.length > 0 ? true : false;
    } else {
      catAcces = false;
    }
    return catAcces;
  }

  function checkProfil(profil: profil): boolean {
    getUser();
    return user.value.profil.find((e) => e === profil) ? true : false;
  }

  //
  // on mémorise l'utilisateur connecté
  function setUser(userData: UserType) {
    setCookieUser(userData);
    if (userData) {
      user.value = userData;
    }
  }

  // on mémorise le token
  function InitToken(token: stringNullable) {
    if (token) setToken(token);
  }

  // Effacement du token
  function ResetToken() {
    removeToken();
  }

  // Effacement des data user
  function ResetUser() {
    removeCookieUser();
    user.value = defaultUser;
  }

  //
  // mise à jour du profil
  async function updateUser(userData: UserType) {
    const { status, message } = await setDataUser(userData);
    if (status) {
      setUser(userData);
    } else {
      ihmModule.displayError({
        code: 'CXUU',
        param: message,
      });
      ResetUser();
    }
    return status;
  }

  //
  // contrôle du token sur les changements de pages
  async function checkToken() {
    //
    // lecture du token

    const { status, message } = await controleToken();

    // on estime que le token est valide
    if (status) {
      console.log('token valide');
      return true;
      // on estime que le token n'est pas valide
    } else {
      // on efface les traces de connexion
      ResetUser();

      ihmModule.displayError({
        code: 'CXCK',
        param: message,
      });

      return false;
    }
  }

  //
  // déconnexion de l'utilisateur
  async function deconnexion(displayOff = false) {
    try {
      // on efface les data user
      ResetUser();
      // on efface le token
      ResetToken();
      if (!displayOff) ihmModule.displayInfo({ code: 'CXOD' });
    } catch (error) {
      ihmModule.displayError({ code: 'CNDK', param: error.message });
    }
  }

  //
  // fonction de modification de l'email d'un tuilisateur
  async function updateEmail(newEmail: string) {
    const userData = user.value;
    userData.email = newEmail;
    const { status, message } = await setDataUser(userData);

    if (status) {
      user.value.email = newEmail;
      ihmModule.displayInfo({ code: 'CXNE', param: newEmail });
    } else {
      ihmModule.displayError({
        code: 'CXEK',
        param: message,
      });
    }
    ihmModule.displayInfo({ code: 'deconnexion' });
  }

  // fonction de modification du mot de passe d'un utilisateur
  async function updateMdpUser(newPassword: string | null) {
    if (user.value.id) {
      //
      // lecture du token
      const userData = user.value;
      userData.password = newPassword;
      const { status, message } = await updateMdp(user.value);
      if (status) {
        // affichage du message : modif ok
        ihmModule.displayInfo({
          code: 'CXPW',
        });
      }
      // l'API a rencontré une erreur
      else {
        ihmModule.displayError({
          code: 'ADMIN',
          param: message,
        });
      }
      //
      // on déconnecte après une modif de password
      deconnexion();
      return status;
    } else {
      ihmModule.displayError({
        code: 'ADMIN',
        param: 'le state user est incohérent',
      });
      deconnexion();
    }
  }

  async function getAvatarUser(
    userId: number
  ): Promise<{ avatar: string; nomaffiche: string }> {
    const { data, status, message } = await getDataAvatar({
      id: userId,
      type: K_avartarUser,
    });
    if (status) {
      if (Array.isArray(data)) {
        return data[0];
      } else {
        return { avatar: '', nomaffiche: '' };
      }
    } else {
      ihmModule.displayError({
        code: 'ADMIN',
        param: message,
      });
      return { avatar: '', nomaffiche: '' };
    }
  }

  async function getAvatarSociete(userId: number) {
    const { data, status, message } = await getDataAvatar({
      id: userId,
      type: K_avartarSociete,
    });
    if (status) {
      if (Array.isArray(data)) {
        return data[0];
      } else {
        return { avatar: '', nomaffiche: '' };
      }
    } else {
      ihmModule.displayError({
        code: 'ADMIN',
        param: message,
      });
    }
  }
  return {
    getProfil,
    getUser,
    getSocieteId,
    getUserId,
    getNomUtilisateur,
    getCategories,
    userIsDesign,
    userIsCommercial,
    userIsClient,
    isSuperUtilisateur,
    userIsAdmin,
    userPlanAcces,
    InitToken,
    setUser,
    userCatAcces,
    isAuthenticated,
    checkProfil,
    updateUser,
    checkToken,
    deconnexion,
    updateMdpUser,
    updateEmail,
    getAvatarUser,
    getAvatarSociete,
  };
});
