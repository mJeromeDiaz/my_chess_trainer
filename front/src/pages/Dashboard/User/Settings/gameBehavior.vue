<template>
  <div class="colum q-gutter-y-md">

    <q-card flat>
      <q-toolbar class="bg-primary">
        <q-toolbar-title>{{ t("gameBehaviorSettings.display.title") }}</q-toolbar-title>
      </q-toolbar>
      <q-card-section>
          <q-form class="q-gutter-y-sm">
          <label>{{ t("gameBehaviorSettings.display.animation.label") }}</label>
          <div>
            <q-btn-toggle
            v-model="settings.waintingTime"
            spread unelevated
            toggle-color="primary"
            :options="[
              { label: t('gameBehaviorSettings.display.animation.choice.slow'), value: '300'},
              { label: t('gameBehaviorSettings.display.animation.choice.normal'), value: '200'},
              { label: t('gameBehaviorSettings.display.animation.choice.fast'), value: '10'}
            ]"
          />
        </div>
        </q-form>
      </q-card-section>
      <q-separator></q-separator>
      <q-card-actions align="justify">
        <q-btn class="" color="primary" :label="t('update')"></q-btn>
      </q-card-actions>
    </q-card>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue"

import { useI18n } from "vue-i18n";

const { t } = useI18n();
const settings = ref({ waintingTime: 200 })

function getPreference(){
  settings.value = { waintingTime: 200 }
}

onMounted(()=>{
  getPreference()
})
</script>

