// définition de l'interface d'un token
export type stringNullable = string | null;

export interface UserType {
  id?: number | null;
  email: string;
  name: stringNullable;
  password?: PasswordType;
  token?: string;
  pro: boolean;
}

export interface PasswordType {
  saisi: string;
  confirmation?: string;
}
export const defaultUser: UserType = {
  email: '',
  name: null,
  password: { saisi: '', confirmation: '' },
  pro: false,
};
