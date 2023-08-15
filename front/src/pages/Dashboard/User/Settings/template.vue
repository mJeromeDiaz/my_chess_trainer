<template>
  <div class="colum q-gutter-y-md">

    <q-card flat>
      <q-toolbar class="bg-primary">
        <q-toolbar-title>{{ t("templateSettings.title") }}</q-toolbar-title>
      </q-toolbar>
      <q-card-section>
          <q-form class="q-gutter-y-sm">
          <label>{{ t("templateSettings.label") }}</label>
          <q-input outlined dense v-model="user.template"></q-input>
          <caption>{{  t("templateSettings.caption") }}</caption>
        </q-form>
      </q-card-section>
      <q-separator></q-separator>
      <q-card-actions align="justify">
        <q-btn class="" color="primary" :label="t('update')"></q-btn>
      </q-card-actions>
    </q-card>

    <q-card flat>
      <q-toolbar class="bg-primary">
        <q-toolbar-title>{{ t("templateSettings.title") }}</q-toolbar-title>
      </q-toolbar>
      <q-card-section>
          <q-form class="q-gutter-y-sm">
          <label>{{ t("templateSettings.label") }}</label>
          <q-input outlined dense v-model="user.template"></q-input>
          <caption>{{  t("templateSettings.caption") }}</caption>
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
import { ref, onMounted } from "vue"

import { useI18n } from "vue-i18n";

const { t } = useI18n();
const user = ref({})

function me(){
  user.value = { nickname: "Shoooryuken" }
}

onMounted(()=>{
  me()
})
</script>

