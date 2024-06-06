export const routes = [
  {
    path: '/adm',
    component: () => import('src/layouts/connexion/PageMain.vue'),
    children: [
      {
        path: 'modifpwd/:token/email/:email',
        name: 'modifpwd',
        props: true,
        component: () => import('src/views/connexion/ChangerMotDePasse.vue'),
      },
    ],
  },
  {
    path: '/',
    redirect: { path: '/accueil' },
    component: () => import('src/layouts/default/PageMain.vue'),
    children: [
      {
        path: 'accueil',
        name: 'accueil',
        component: () => import('src/views/evenement/MesEvenements.vue'),
      },
      {
        path: 'connexion',
        name: 'connexion',
        component: () => import('src/views/connexion/Connexion.vue'),
      },
      {
        path: 'MotDePasseOublier',
        name: 'MotDePasseOublier',
        component: () => import('src/views/connexion/MotDePasseOublier.vue'),
      },
      {
        path: 'register',
        name: 'register',
        component: () => import('src/views/connexion/CreationCompte.vue'),
      },
      {
        path: 'profil',
        name: 'profil',
        component: () => import('src/views/admin/FormAdmin.vue'),
        meta: {
          requiresAuth: true,
        },
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('src/views/ErrorNotFound.vue'),
  },
];
