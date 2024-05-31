export type stringNullable = string | null;

export interface adresse {
  adr: string;
  latLng: {
    lat: number;
    lng: number;
  };
}

export interface adresseListObject {
  data: string;
  value: adresse;
}