// Composables
import { createRouter, createWebHistory } from 'vue-router';
import { routes } from 'src/router/lstRoute';

import { userStore } from 'src/stores/users';

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// on contrôle si la route est sécurisée ou pas
// si c'est le cas on impose un contrôle du token

router.beforeEach(async (to, from, next) => {
  // chargement du store user
  const userModule = userStore();
  // si on demande la page login on y va directement
  if (to.name === 'connexion') next();
  else {
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    // la page demandée n'est pas sécurisée, on peut l'afficher
    if (!requiresAuth) next();
    else {
      // on est sur une page sécuriée
      // on vérifie que le token est renseigné
      userModule.connected ? next() : next({ name: 'connexion' });
    }
  }
});

export default router;
