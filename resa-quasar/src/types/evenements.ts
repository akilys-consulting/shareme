import { type stringNullable, type adresseListObject } from 'src/types/general';

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
  adresse: adresseListObject;
  options: stringNullable;
  organisateurid?: number;
  description?: string;
  categories?: categorie[];
  prix: stringNullable;
  actif: boolean;
  image?: string;
  site?: string;
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

export const defaultEvenement: EvenementType = {
id: -1,
titre: '',
adresse: {  data:'', value:{adr: '',
  latLng: {
    lat: 0,
    lng: 0
  }}},
options: '',
organisateurid: -1,
description: '',
categories: [],
prix: '',
actif: true,
image: '',
site: ''
}
