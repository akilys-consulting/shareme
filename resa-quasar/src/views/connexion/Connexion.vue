<!-- eslint-disable vue/multi-word-component-names -->

<template>
  <q-card
    style="width: 50%"
    flat
    bordered
    class="card-login fixed-center vertical-middle"
  >
    <q-form @submit="connexion" class="q-gutter-md">
      <q-card-section>
        <div class="column">
          <div class="col">
            <q-input
              filled
              v-model="email"
              lazy-rules
              :rules="[checkRules.required, checkRules.email]"
              label="Votre email"
            >
              <template v-slot:prepend>
                <q-icon name="person" />
              </template>
            </q-input>
          </div>
          <div class="col">
            <q-input
              v-model="password"
              label="mot de passe"
              filled
              lazy-rules
              :rules="[checkRules.required]"
              :type="visiblePwd ? 'text' : 'password'"
            >
              <template v-slot:prepend>
                <q-icon name="key" />
              </template>
              <template v-slot:append>
                <q-icon
                  :name="visiblePwd ? 'visibility_off' : 'visibility'"
                  class="cursor-pointer"
                  @click="visiblePwd = !visiblePwd"
                />
              </template>
            </q-input>
          </div>
          <div class="col">
            <div class="col-4">
              <q-btn
                flat
                class="float-right"
                no-caps
                label="Créer un compte"
                @click="creerCompte"
              />
            </div>
            <div class="col-4">
              <q-btn
                flat
                class="float-right"
                no-caps
                label="Mot de passe oublié"
                :to="{ name: 'MotDePasseOublier' }"
              />
            </div>
            <div class="col-4">
              <q-btn @click="loginWithGoogle" no-caps flat class="float-left">
                <q-avatar size="22px">
                  <img src="/icons/google_cnx.png" />
                </q-avatar>
                Se connecter avec google
              </q-btn>
            </div>
          </div>
        </div>
      </q-card-section>
      <q-card-actions class="justify-center">
        <q-btn type="submit" outline color="primary" flat>connexion</q-btn>
        <q-btn flat :to="{ name: 'connexion' }" color="secondary"
          >Annuler</q-btn
        >
      </q-card-actions>
    </q-form>
  </q-card>
</template>

<script setup lang="ts">
import { checkRules } from 'src/utils/config';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

import { login } from 'src/api/users';
import { ihmStore } from 'src/stores/ihm';
import { userStore } from 'src/stores/users';
const userModule = userStore();
const IhmModule = ihmStore();
const router = useRouter();

const visiblePwd = ref(false);
const email = ref();
const password = ref();

/**
 * fonction de validation de la connexion
 */
async function connexion() {
  //
  //
  try {
    // contrôle de la connexion en base
    IhmModule.startWaiting();
    let { status, message } = await login({
      email: email.value,
      password: password.value,
    });
    IhmModule.stopWaiting();

    // connexion acceptée
    if (status) {
      // pas de message sur une connexion
      //IhmModule.displayInfo({ code: "CXOL" });
      userModule.setUserConnected();
      userModule.refreshConnected();
      // token validé, on peut aller sur l'accueil
      router.push({ name: 'accueil' });
      //
      // message de déconnexion
      IhmModule.displayInfo({ code: 'CXOL' });
    } else {
      IhmModule.displayError({ code: 'CXPL', param: message });
    }
  } catch (error) {
    let message = '';
    if (error instanceof Error) message = error.message;
    else message = String(error);
    IhmModule.displayError({ code: 'CXPL', param: message });
  }
}

//
// permet d'appeler la form de création d'un compte
function creerCompte() {
  router.push({ name: 'register' });
}

function loginWithGoogle() {
  window.location.href = 'api/auth/google';
}
</script>

<style scoped>
.login {
  margin-top: 15%;
  opacity: 0.8;
}

.q-field__inner {
  background-color: rgba(94, 26, 26, 0.178) !important;
}

.card-login {
  background-color: rgba(158, 108, 106, 0.123);
}

.icon_image {
  width: auto;
  height: auto;
  max-height: 2em;
  max-width: 2em;
  margin-top: -2px;
  margin-right: 10px;
}

.button:hover {
  background-color: rgb(129, 12, 12);
  transition: 0.7s;
}

.btn-active {
  background-color: rgb(129, 12, 12);
}
</style>
