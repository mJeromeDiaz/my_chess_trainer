const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      { path: "", component: () => import("pages/Index.vue") },
      { path: 'woodpecker', component: () => import("pages/Woodpecker.vue") },
      { name: 'puzzle', path: "puzzle/:id", component: () => import("pages/Puzzle.vue") },
      { name: 'signup', path: "singup", component: () => import("pages/User/Signup.vue") },
    ],
  },
  {
    path: "/dashboard",
    meta: { auth: true },
    component: () => import("layouts/DashboardLayout.vue"),
    children: [
      { path: "", component: () => import("pages/Dashboard/Index.vue") },
    ],
  },
  {
    path: "/dashboard/woodpecker/",
    component: () => import("layouts/DashboardLayout.vue"),
    children: [
      { path: "", component: () => import("pages/Dashboard/Index.vue") },
      { name: 'woodpeckerSetup', path: "setup", component: () => import("pages/Dashboard/Woodpecker/WoodpeckerSetup.vue") },
      { name: 'woodpeckerStatistiques', path: "statistiques", component: () => import("pages/Dashboard/Woodpecker/WoodpeckerStatistiques.vue") },
    ],
  },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/Error404.vue"),
  },
];

export default routes;
