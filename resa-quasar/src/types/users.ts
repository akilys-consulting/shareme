import { type confSociete } from 'src/types/configuration';

// définition de l'interface d'un token
export type stringNullable = string | null;

// définition de l'interface de gestion des utilisateurs
export interface profil {
  value: string;
  key: string;
}

export const K_su = 'SU';
export const K_admin = 'AD';
export const K_design = 'DE';
export const K_commercial = 'CO';
export const K_client = 'AU';
export const K_avartarSociete = 'soc';
export const K_avartarUser = 'usr';

//
// définition des profils
// Superadmin : accès users toutes sociétés, création société
// admin      : accès users sur societe courante
// design     : modif plan event, modif event toutes société, ajout event
// commercial : modif event société courante,
// autre      : juste consultation tous les evènements
export const profilList = [
  { value: 'SuperAdmin', key: 'SU' },
  { value: 'design', key: 'DE' },
  { value: 'commercial', key: 'CO' },
  { value: 'admin', key: 'AD' },
  { value: 'autre', key: 'AU' },
];

export interface UserType {
  id?: number | null;
  email: string;
  name: stringNullable;
  profil?: profil[];
  password?: PasswordType;
  token?: string;
}

export interface PasswordType {
  saisi: string;
  confirmation?: string;
}
export const defaultUser: UserType = {
  email: '',
  name: null,
  password: { saisi: '', confirmation: '' },
};

export interface confSociete {
  categorie?: categorie[];
  plan?: boolean;
}
export interface SocieteType {
  id: number | null;
  nom: string;
  adresse?: adresseListObject;
  email_contact?: string;
  avatar?: string;
  telephone?: number;
  actif: boolean;
  conf: confSociete;
}
