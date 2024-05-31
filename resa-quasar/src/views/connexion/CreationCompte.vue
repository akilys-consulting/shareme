
<template>
  <q-card style="width: 50%" flat bordered class="card-login fixed-center vertical-middle">
    <q-form @submit="creationCompte" class="q-gutter-md">
      <q-card-section>
        <div class="column">
          <div class="col">
            <q-input filled v-model="dataForm.name" lazyrules :rules="[checkRules.required]" label="Nom affiché" />
          </div>
          <div class="col">
            <q-input filled v-model="dataForm.email" lazyrules :rules="[checkRules.required, checkRules.email]"
              label="Votre email">
              <template v-slot:prepend>
                <q-icon name="person" />
              </template>
            </q-input>
          </div>
          <div class="col">
            <q-input v-model="dataForm.password.saisi" label="mot de passe"
              :rules="[checkRules.required, checkRules.password]" filled :type="showPassword ? 'text' : 'password'">
              <template v-slot:prepend>
                <q-icon name="key" />
              </template>
              <template v-slot:append>
                <q-icon :name="showPassword ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                  @click="showPassword = !showPassword" />
              </template>
            </q-input>
          </div>
          <div class="col">
            <q-input v-model="dataForm.password.confirmation" label="mot de passe de confirmation"
              :rules="[checkRules.required, checkRules.password]" filled :type="showPasswordRedo ? 'text' : 'password'">
              <template v-slot:prepend>
                <q-icon name="key" />
              </template>
              <template v-slot:append>
                <q-icon :name="showPasswordRedo ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                  @click="showPasswordRedo = !showPasswordRedo" />
              </template>
            </q-input>
          </div>

        </div>
      </q-card-section>
      <q-card-actions class="justify-center">
        <q-btn type="submit" no-caps outline color="primary" flat>connexion</q-btn>
        <q-btn flat :to="{ name: 'connexion' }" no-caps color="secondary">Annuler</q-btn>
      </q-card-actions>
    </q-form>
  </q-card>
</template>


<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { type UserType, defaultUser } from 'src/types/users';
import { createAccount } from 'src/api/users'
import { ihmStore } from 'src/stores/ihm';
import { checkRules } from 'src/utils/config'


const dataForm = ref<UserType>(defaultUser)
const showPassword = ref(false)
const showPasswordRedo = ref(false)

const IhmModule = ihmStore()
const router = useRouter()

async function creationCompte() {
  //
  //
  if (dataForm.value.password && dataForm.value.password.saisi == dataForm.value.password.confirmation) {
    try {
      // on demande l'ajout de l'utilisateur
      let { status, message } = await createAccount(
        {
          'email': dataForm.value.email, 'nomAffiche': dataForm.value.nomAffiche,
          password: { 'saisi': dataForm.value.password.saisi, 'confirmation': dataForm.value.password.confirmation }
        }
      );
      // connexion acceptée
      if (status) {
        IhmModule.displayInfo({ code: 'CXOL' });
        // création correcte, on onriente l'utilisateur sur la page de connexion
        router.push({ name: 'connexion' });
      } else {
        // création en erreur
        IhmModule.displayError({ code: 'CXPL', param: message });

      }
    } catch (error) {
      let message = ''
      if (error instanceof Error) message = error.message
      else message = String(error)
      IhmModule.displayError({ code: 'CXPL', param: message });
    }
  } else {
    IhmModule.displayError({ code: 'CXPD' });
  }
}


</script>

<style>
.card-login {
  background-color: rgba(61, 15, 15, 0.144);
}
</style>
