<template>
  <v-tab-item>
    <v-card-text>
      <v-row v-if="!displayFrmEmail && !displayFrmPassword">
        <v-col cols="12">votre email {{ user.email }} </v-col>

        <v-col cols="6">
          <v-btn text x-small plain @click="displayFrmEmail = true">
            changer mon email
          </v-btn>
        </v-col>
        <v-col cols="6">
          <v-btn text x-small plain @click="activeFrmPassword">
            changer mot de passe
          </v-btn>
        </v-col>
      </v-row>
      <v-row v-if="displayFrmEmail">
        <v-col cls="12">votre email : {{ user.email }}</v-col>
        <v-col cols="12">
          <v-text-field :rules="[rules.required, rules.email]" v-model="newEmail" label="Nouveau email"></v-text-field>
        </v-col>
        <v-col cols="6"><v-btn text x-small plain @click="updateEmail()"> valider </v-btn> </v-col><v-col cols="6"><v-btn
            text x-small plain @click="displayFrmEmail = !displayFrmEmail">
            annuler
          </v-btn>
        </v-col>
      </v-row>
      <v-row v-if="displayFrmPassword">
        <v-col cols="12">
          <v-text-field disabled filled dense flat :label="user.email"></v-text-field>
          <span v-if="errorMsg" class="orange--text">Attention : {{ errorMsg }}</span>
        </v-col>

        <v-col cols="6">
          <v-text-field prepend-icon="mdi-lock" :rules="[rules.required]"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" :type="showPassword ? 'text' : 'password'"
            label="mot de passe" v-model="password" @click:append="showPassword = !showPassword" /> </v-col><v-col
          cols="6">
          <v-text-field prepend-icon="mdi-lock" :append-icon="showPasswordRedo ? 'mdi-eye' : 'mdi-eye-off'"
            :type="showPasswordRedo ? 'text' : 'password'" name="input-10-2" label="confirmation mot de passe"
            v-model="passwordRedo" @click:append="showPasswordRedo = !showPasswordRedo" />
        </v-col>
        <v-col cols="6"><v-btn text x-small plain @click="updatMotDePasse()"> valider </v-btn>
        </v-col>
        <v-col cols="6"><v-btn text x-small plain @click="DesactiveFrmPassword"> annuler </v-btn>
        </v-col>
      </v-row>
    </v-card-text>
  </v-tab-item>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { userStore } from 'src/stores/users';
import { useRouter } from 'vue-router'

const connexionModule = userStore()
const router = useRouter()

const rules = {
  required: (value: string) => !!value || 'obligatoire.',
  email: (value: string) => {
    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pattern.test(value) || "ce n'est pas un e-mail";
  },
};
const displayFrmEmail = ref(false)
const displayFrmPassword = ref(false)
const newEmail = ref('')
const password = ref(null)
const passwordRedo = ref(null)
const showPassword = ref(false)
const showPasswordRedo = ref(false)
const errorMsg = ref('')

const user = computed(() => {
  return connexionModule.getUser
})

function updateEmail() {
  connexionModule.updateEmail(newEmail.value);
  router.push({ name: 'connexion' });
}

function activeFrmPassword() {
  displayFrmPassword.value = true;
}

function DesactiveFrmPassword() {
  displayFrmPassword.value = false;
}

async function updatMotDePasse() {
  if (password.value !== passwordRedo.value) {
    errorMsg.value = 'les mots de passe sont différents';
  } else if (!password.value || !passwordRedo.value) {
    errorMsg.value = 'un des mots de passe est vide';
  } else {
    await connexionModule.updateMdp(password.value);
    DesactiveFrmPassword();
    router.push({ name: 'connexion' });
  }
}
</script>

<style></style>
