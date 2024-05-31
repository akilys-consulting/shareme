<script setup lang="ts">
import { useQuasar,QSpinnerGrid } from 'quasar'
import { onBeforeUnmount } from 'vue'


const $q = useQuasar()
let timer

function showLoading () {
        $q.loading.show({
          spinner: QSpinnerGrid,
          spinnerColor: 'cyan',
          spinnerSize: 140,
          backgroundColor: 'rgba(94, 26, 26, 0.178)',
          message: 'Action en cours...',
          messageColor: '#a7293e'
        })

        // hiding in 3s
        timer = setTimeout(() => {
          $q.loading.hide()
          timer = void 0
        }, 3000)
      }
    }
  }
}
</script>
