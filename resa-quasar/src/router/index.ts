// Composables
import { createRouter, createWebHistory } from 'vue-router';
import { routes } from 'src/router/lstRoute';



const router = createRouter({
 history: createWebHistory(),
  routes,
});

// on contrôle si la route est sécurisée ou pas
// si c'est le cas on impose un contrôle du token

router.beforeEach(async (to, from, next) => {

  // si on demande la page login on y va directement
  if (to.name === 'connexion') next();
  else {
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    // la page demandée n'est pas sécurisée, on peut l'afficher
    if (!requiresAuth) next();
    // on est sur une page sécuriée
    else {
     next();
      }
  }
});

export default router;
