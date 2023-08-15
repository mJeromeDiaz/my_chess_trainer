<template>
  <q-page class="container q-py-lg">
    <div class="q-pa-md row q-gutter-md">

      <nav class="col">
        <ul>
          <li v-for="(link, i) in links" :key="i" :class="link.label === route.params.current ? 'active' : 'cursor-pointer'" @click="router.push({ name: 'settings', params: { current: link.label }})">
            <span><q-icon size="xs" class="icon q-mx-sm" :name="link.icon"></q-icon></span>
            <span>{{  t(link.label) }}</span>
          </li>
        </ul>
      </nav>

      <section class="col-xs-12 col-md-6 col-lg-9">
        <q-card flat class="bg-teal text-white">
          {{  route.params.current }}
          <q-card-section class="bg-primary text-white">
            <component :is="getComponent(route.params.current)"></component>
          </q-card-section>
        </q-card>
      </section>

    </div>
  </q-page>
</template>

<script setup>
import { useI18n } from "vue-i18n";
import { useRoute, useRouter } from "vue-router"

const { t } = useI18n();
const route = useRoute();
const router = useRouter();

const getComponent = (name) => import(`./Settings/${name}.vue`);

const links = [
  { label: "account", icon: "person" },
  { label: "gameBehavior", icon: "sports_esports" }
]
</script>
