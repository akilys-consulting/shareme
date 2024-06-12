import { type stringNullable, type adresseListObject } from 'src/types/general';
import { type ProgrammationType } from 'src/types/programmation_evt';

export type periode = 'days' | 'weeks' | 'months' | '*';

export type categoriesType = string[];

export interface categorie {
  text: string;
  color: string;
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
  auteurid?: number;
  categories?: categorie[];
  recurrence_set: boolean;
  date_debut: Date;
  date_fin: Date;
  recurrence?: ProgrammationType;
  actif: boolean;
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
  recurrence_set: false,
  date_Debut: Date.now(),
  date_fin: Date.now(),
  actif: false,
};
