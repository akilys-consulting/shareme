import { type stringNullable, type adresseListObject } from 'src/types/general';
import { type ProgrammationType } from 'src/types/programmation_evt';

export type periode = 'days' | 'weeks' | 'months' | '*';

export type categoriesType = string[];

export interface categorie {
  text: string;
  color: string;
}

export interface filterEventType {
  societeId: number;
  date: string;
  cat: categoriesType;
  search: string;
}

export interface EvenementType {
  id: number;
  titre: stringNullable;
  description?: string;
  adresse: adresseListObject;
  auteurid?: number;
  categories?: categorie[];
  recurrence_set: boolean;
  date_debut: Date;
  date_fin: Date;
  recurrence?: ProgrammationType;
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
