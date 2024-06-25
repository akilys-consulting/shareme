<template>
    <div class="q-pa-md">
        <q-table flat bordered title="Sorties proposées" :rows="rows" :columns="columns" row-key="name">
            <template v-slot:body="props">
                <q-tr :props="props" @click="onRowClick(props.row)">
                    <q-td key="avatar" :props="props">
                        <q-chip>
                            <q-avatar>
                                <img :src="'./public/' + props.row.avatar"></q-avatar>
                            {{ props.row.avatar }}
                        </q-chip></q-td>
                    <q-td key="description" :props="props">
                        {{ props.row.desc }}
                    </q-td>
                    <q-td key="date" :props="props">
                        {{ props.row.dt }}
                    </q-td>
                    <q-td key="lieu" :props="props">
                        {{ props.row.lieu }}
                    </q-td>
                    <q-td key="nombre" :props="props">
                        <q-badge align="middle" color="teal">
                            {{ props.row.nb }}
                        </q-badge>
                    </q-td>

                </q-tr>
            </template>
        </q-table>
    </div>
</template>

<script setup lang="ts">
import type { QTableProps } from 'quasar';
import { ref } from 'vue'


const columns: QTableProps['columns'] = [
    { name: 'avatar', align: 'center', label: 'organisateur', field: 'avatar', sortable: false },
    { name: 'description', align: 'left', label: 'description', field: 'desc', sortable: false },
    { name: 'date', align: 'center', label: 'Date/heure', field: 'dt', sortable: false },
    { name: 'lieu', align: 'left', label: 'Lieu rdv', field: 'lieu', sortable: false },
    { name: 'nombre', align: 'center', label: 'Places', field: 'nb', sortable: false },
]

const rows = ref([
    {
        nb: '6/12',
        desc: 'une petite sortie pour se faire du bien',
        lieu: 'devant l\'entrée',
        dt: "12/07/2023 17h00",
        avatar: "avatar1.png"
    },
    {
        nb: '0/7',
        desc: 'une petite sortie pour se faire du bien',
        lieu: 'on se retrouve directement à l\'intérieur',
        dt: "12/07/2023 17h00",
        avatar: "avatar2.png"

    },])

function onRowClick(row: any) {
    console.log('row', row)
}
</script>