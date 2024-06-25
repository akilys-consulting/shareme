// définition de l'interface de connexion
export interface connexionType {
  email: string;
  password: string;
}

// definition de l'interface de gestion des response API
export interface ApiType {
  status: boolean;
  data?: object[];
  message: string;
  count?: number;
}

export interface evenementCriteriasApiType {
  annee: string;
  organisateur?: string;
}
