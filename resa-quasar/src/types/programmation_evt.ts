
export const K_typeProgrammation = [
  { value: 'days', text: 'journalier' },
  { value: 'weeks', text: 'hebdomadaire' },
  { value: 'months', text: 'mensuel' },
  { value: '*', text: 'permanent' },
];


export const typeProgrammation = [
  'journalier',
  'hebdomadaire',
  'mensuel',
  'annuel',
  'specifique',
];
export interface ProgrammationType {
  id: number | null;
  type: string;
  datedebut: string;
  datefin: string;
  jours?: string[];
  evenementid: number;
}
