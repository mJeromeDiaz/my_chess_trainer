<template>
  <div>
    <q-toolbar class="bg-primary">
      <q-toolbar-title>{{ t("account") }}</q-toolbar-title>
    </q-toolbar>
    <q-card flat>
      <div class="q-pa-md">
        <q-form class="q-gutter-y-sm">
          <section class="q-mb-md">
            <label>{{ t('accountSettings.name.label') }}</label>
            <q-input outlined dense v-model="user.nickname"></q-input>
            <caption>{{  t('accountSettings.name.caption') }}</caption>
          </section>

          <section class="q-mb-md">
            <q-btn class="" color="primary" :label="t('update')"></q-btn>
          </section>
        </q-form>

        <q-separator></q-separator>

        <section class="q-my-md">
          <q-btn class="" color="negative" icon="cancel" :label="t('accountSettings.delete')"></q-btn>
        </section>
      </div>
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

