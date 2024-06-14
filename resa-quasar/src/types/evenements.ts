import { type stringNullable, type adresseListObject } from 'src/types/general';
import {
  type ProgrammationType,
  programmationParDefaut,
} from 'src/types/programmation_evt';

export type periode = 'days' | 'weeks' | 'months' | '*';

export type categoriesType = string[];

export interface categorie {
  id: number;
  titre: string;
  path: string;
  actif: boolean;
}

export interface auteur {
  id: number;
  nom: string;
}
export interface filterEventType {
  date: string;
  cat: categoriesType;
  search: string;
}

export interface EvenementType {
  id: number;
  titre: string;
  description?: string;
  url?: string;
  adresse: adresseListObject;
  auteur: auteur;
  categories?: categorie[];
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

export const evenementVide = {
  id: null,
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
  description: '',
  recurrence: programmationParDefaut,
  actif: false,
  auteur: null,
};
