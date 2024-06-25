<template>
    <div class="text-h4 q-mb-md">Ajouter une sortie </div>
    <q-form class=" q-mt-md">
        <q-input style="max-width:1000px" v-model="nom" label="Titre *" hint="description courte de votre sortie" lazy-rules
            :rules="[val => val && val.length > 0 || 'Please type something']" />
        <div class="row q-pt-md vertical-middle">
            <div class="col-12 col-lg-4 q-mr-lg" style="max-width:300px">
                <q-input v-model="date">
                    <template v-slot:prepend>
                        <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <q-date v-model="date" mask="YYYY-MM-DD HH:mm">
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat />
                                    </div>
                                </q-date>
                            </q-popup-proxy>
                        </q-icon>
                    </template>

                    <template v-slot:append>
                        <q-icon name="access_time" class="cursor-pointer">
                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <q-time v-model="date" mask="YYYY-MM-DD HH:mm" format24h>
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat />
                                    </div>
                                </q-time>
                            </q-popup-proxy>
                        </q-icon>
                    </template>
                </q-input>
            </div>
            <div class="col-12  q-pt-md  col-lg-4">
                <q-item class="slider">
                    <q-item-section avatar>
                        Nb participants max
                    </q-item-section>
                    <q-item-section>
                        <q-slider v-model="nbParticipant" :min="2" :max="nbMaxParticipants" :step="1" label
                            :label-value="nbParticipant + ' personnes'" label-always color="primary" switch-label-side
                            switch-marker-labels-side style="max-width: 600px" />
                    </q-item-section>
                    <q-item-section side>
                        {{ nbMaxParticipants }}
                    </q-item-section>
                </q-item>
            </div>
            <div class="col-12 col-lg-4">
                <div class="q-pt-md" style="max-width: 400px">
                    <div class="text-subtitle1 q-pt-md">Gestion de l'inscription des participants</div>

                    <q-option-group name="inscription" v-model="valid_participants" :options="typeValidation"
                        color="primary" inline><q-tooltip>
                            Permet ou pas une inscription automatique des participants
                        </q-tooltip></q-option-group>

                </div>
            </div>
        </div>

        <div class="text-subtitle1 q-pt-md">Description détaillée</div>
        <q-editor v-model="editor" min-height="15rem" />


        <div class="q-ma-md float-right">
            <q-btn label="créer" type="submit" color="primary" />
            <q-btn label="effacer" type="reset" color="primary" flat class="q-ml-sm" />
        </div>
    </q-form>
</template>
<script setup lang="ts">
import { ref } from 'vue'
const valid_participants = ref(true)
const nom = ref("")
const editor = ref("")
const date = ref('10:56')
const nbParticipant = ref(1)
const nbMaxParticipants = ref(20)
const typeValidation = ref([{ label: 'Par validation', value: 'valid' }, { label: 'Automatique', value: 'auto' }])

</script>
<style>
.slider {
    padding: 0 !important
}
</style>