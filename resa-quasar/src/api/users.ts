import { api } from 'boot/axios';

import { type ApiType, type connexionType } from 'src/api/api_types';
import { type UserType } from 'src/types/users';
import {setTokenCnx,getTokenCnx, removeTokenCnx} from 'src/utils/cookie'

//
// création d'un compte utilisateur
export const createAccount = async (user: UserType): Promise<ApiType> => {
  // appel à l'api pur enregister le compte
  try {
    if (user.password) {
      //await api.get('/sanctum/csrf-cookie');
      const request = await api.post('/register', {
        name: user.name,
        email: user.email,
        password: user.password.saisi,
        password_confirmation: user.password.confirmation,
      });
      //
      // stockage du token
      setTokenCnx(request.data.access_token);
      return { status: true, message: 'utilisateur créé' };
    } else {
      return { status: false, message: 'mot de passe vide' };
    }
  } catch (error) {
    return { status: false, message: (error as Error).message };
  }
};

export const login = async (data: connexionType): Promise<ApiType> => {
  try {
    const request = await api.post('/login', {
      email: data.email,
      password: data.password,
    });
      //
      // stockage du token
    setTokenCnx(request.data.access_token);
    return { status: true, message: 'utilisateur connecté' };
  } catch (error: any) {
    return { status: false, message: error.response.data.message };
  }
};

//
// récupération d'un token
// permettant de chnager son mot de passe
export const API_demandeModifierMdp = async (data: {
  email: string;
}): Promise<ApiType> => {
  try {
    const token = getTokenCnx();
    const request = await api.post('forgot-password', {email: data.email},{  headers: {Authorization: `Bearer ${token}`}});
    if ( request.status === 200){
      // lecture du retour
        return { status: true, data:[{}], message: request.message };
      }
  }catch (error) {
    return { status: false, data:[{}], message: (error as Error).message };
  }

}

//
// récupération d'un token
// permettant de chnager son mot de passe
export const API_EnvoimodifierMdp = async (user: UserType): Promise<ApiType> => {
  try {
    // appel de l'API pour modifier le mot de passe du mail email
    const request = await api.post('/reset-password', {
      // mail de l'utilisateur qui fait la demande
      email: user.email ,
      // ,nouveau mot de passe + confirmation
      password: user.password.saisi,
      password_confirmation: user.password.confirmation,
      // token validant la demande
      token: user.token
    });
    //
    // en retour on contrôle le status
    if ( request.status === 200 ){
      // lecture du retour
      return { status: true, data:[{email:user.email}], message: 'modifiy password' };
    } else {
      return { status: false, data:[{email:user.email}], message: 'modifiy password' };
    }
  }catch (error) {
    return { status: false, data:[{email:user.email}], message: (error as Error).response.data.message };
    throw error;
  }

}
//
// fonction permettant à un utilisateur de se déconnecter
//
export const logout = async () => {
  try {
    const token = getTokenCnx();
    const request = await api.post('/logout',{},
      {  headers: {Authorization: `Bearer ${token}`}}
    );
    const response = await request.data;
    // suppresion du token
    removeTokenCnx()
    return response;
  } catch (error) {
    return { status: false, message: (error as Error).message };
  }
};

//
// fonction permettant de lire le profil
// d'un utilisateur
//
export const readProfil = async() :Promise<ApiType> => {
  try {
    const token = getTokenCnx();
    const request = await api.get('/profil',
    {  headers: {Authorization: `Bearer ${token}`}});
    //
    // récupération des infos
    const response = await request.data;
    console.log ('response',response)
    return  { status: true, data: [response], message:'profil récupéré' };
  } catch (error) {
    return { status: false, message: (error as Error).message };
  }
}
