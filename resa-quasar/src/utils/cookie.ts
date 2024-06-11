import { type filterEventType } from 'src/types/evenements';
import { Cookies } from 'quasar';
import { type UserType, defaultUser } from 'src/types/users';

// App
const sidebarStatusKey = 'sidebar_status';
export const getSidebarStatus = () => Cookies.get(sidebarStatusKey);
export const setSidebarStatus = (sidebarStatus: string) =>
  Cookies.set(sidebarStatusKey, sidebarStatus);

const languageKey = 'language';
export const getLanguage = () => Cookies.get(languageKey);
export const setLanguage = (language: string) =>
  Cookies.set(languageKey, language);

const sizeKey = 'size';
export const getSize = () => Cookies.get(sizeKey);
export const setSize = (size: string) => Cookies.set(sizeKey, size);

//
// Gestion de la mémorisation du token de connexion
const tokenKey = 'event_token';
export const getTokenCnx = () => {
  return Cookies.get(tokenKey);
};
export const setTokenCnx = (token: string) =>
  Cookies.set(tokenKey, token, { expires: '40m' });

//
// suppression du token de connexion
export const removeTokenCnx = () => Cookies.remove(tokenKey);

//
// Gestion de la mémorisdation du profil utilisateur
const userKey = 'resa4AllUser';
export const getCookieUser = () => {
  let response = defaultUser;
  if (Cookies.get(userKey)) response = Cookies.get(userKey);
  return response;
};
export const setCookieUser = (user: UserType) =>
  Cookies.set(userKey, JSON.stringify(user));
export const removeCookieUser = () => Cookies.remove(userKey);

//
// cookie sur filtre evènement
const FilterKey = 'resa4AllEvtFilter';
export const setFilterEVt = (criteres: filterEventType) =>
  Cookies.set(FilterKey, JSON.stringify(criteres));

export const getFilterEVt = (): filterEventType => {
  let reponse: filterEventType = { search: '', cat: [], date: '' };
  if (Cookies.has(FilterKey)) reponse = Cookies.get(FilterKey);
  return reponse;
};
export const removeFilterEvt = () => Cookies.remove(FilterKey);
