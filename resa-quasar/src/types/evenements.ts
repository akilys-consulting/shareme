import { type adresseListObject } from 'src/types/general';
import {
  type ProgrammationType,
  programmationParDefaut,
} from 'src/types/programmation_evt';

export type periode = 'days' | 'weeks' | 'months' | '*';

export type categoriesType = string[];

export interface auteur {
  id: number;
  name: string;
}
export interface filterEventType {
  date: string;
  cat: categoriesType;
  search: string;
}

export interface API_EvenementType {
  id: number;
  titre: string;
  description?: string;
  url?: string;
  adresse: string;
  auteur: string;
  categories: string;
  recurrence: string;
  actif: boolean;
  event_id?: number;
  image?: string;
  nb_personnes?: number;
}

export interface EvenementType {
  id: number;
  titre: string;
  description?: string;
  url?: string;
  adresse: adresseListObject;
  auteur: auteur;
  categories: categoriesType;
  recurrence: ProgrammationType[];
  actif: boolean;
  event_id?: number;
  image?: string;
  nb_personnes?: number;
}

export interface PlanningType {
  dtDebut: Date;
  dtFin: Date;
}

export interface typePlanning {
  start: string;
  end: string;
  permanentDays?: string[];
}

export const nomCategories = {
  sortie: 'Sortie',
  theatre: 'Théatre',
  randonnee: 'Randonnée',
  concert: 'Concert',
};

export const listCategories = [
  nomCategories.sortie,
  nomCategories.theatre,
  nomCategories.randonnee,
  nomCategories.concert,
];
export const evenementVide: EvenementType = {
  id: -1,
  titre: 'Nouveau titre',
  adresse: {
    data: '',
    value: {
      adr: '',
      latLng: {
        lat: 0,
        lng: 0,
      },
    },
  },
  categories: [nomCategories.sortie],
  description: '',
  recurrence: programmationParDefaut,
  actif: false,
  auteur: { id: -1, name: '' },
};
