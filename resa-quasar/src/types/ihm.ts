export interface messageType {
  display?: boolean;
  code: string;
  param?: string | null;
  type?: string;
}

export interface question {
  code: string;
  display: boolean;
}

export interface headerTab {
  text: string;
  value: string;
  sortable: boolean;
}

export interface lstOptionsDialog {
  color: string;
  width: number;
  titre: string;
}

export interface imageUpload {
  data: string;
  fichier: string;
  type: string;
}
export enum displayCategorie {
  tab,
  page,
}
