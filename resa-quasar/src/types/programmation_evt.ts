import { date } from 'quasar';

export const K_typeProgrammation = [
  { value: 'days', label: 'journalier' },
  { value: 'weeks', label: 'hebdomadaire' },
  { value: 'months', label: 'mensuel' },
  { value: '*', label: 'permanent' },
];

export const typeProgrammation = [
  'journalier',
  'hebdomadaire',
  'mensuel',
  'annuel',
  'specifique',
];

export interface ProgrammationType {
  type: string;
  datedebut: string;
  datefin: string;
  jours?: string[];
}

export const programmationParDefaut = [
  {
    id: null,
    type: K_typeProgrammation[0].value,
    datedebut: date.formatDate(Date.now(), 'DD/MM/YYYY HH:mm'),
    datefin: date.formatDate(Date.now(), 'DD/MM/YYYY HH:mm'),
  },
];
