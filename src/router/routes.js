import puzzle from 'src/components/Puzzle.vue'
import Woodpecker from 'src/components/Woodpecker.vue';

const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      { path: "", component: () => import("pages/Index.vue") },
      { path: "puzzle/:id", component: (puzzle) },
      { path: "woodpecker", component: (Woodpecker) }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/Error404.vue"),
  },
];

export default routes;
