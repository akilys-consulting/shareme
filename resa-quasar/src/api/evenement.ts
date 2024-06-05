import { api } from 'boot/axios';
import { type ApiType } from 'src/api/api_types';
import {getTokenCnx} from 'src/utils/cookie'


//
// permet de récupérer tous les enregistrements evènments
export const listEvenements = async (): Promise<ApiType> => {
  const token = getTokenCnx();
  try {
  const response= await api.get('getAllEvt', {
    headers: {Authorization: `Bearer ${token}`}
  });
  if ( response.status === 200){
    // lecture du retour
      return { status: true, data:response.data.evenements, message: 'list evet ok' };
    }
  }  catch (error) {
    return { status: false, data:[{}], message: (error as Error).message };
  }
};
/*
//
// permet de récupérer tous les enregistrements evènments
export const getEvenement = async (data: { id: string }): Promise<ApiType> => {
  const request = await api.post('appli/getEvenement', {
    headers: getAuthToken(),
    method: 'POST',
    id: data.id,
  });
  const response = await request.data;
  return response;
};
//
// fonction d'envoi des données à l'API de sauvegarde des évènements
export const saveEvenements = async (
  data: EvenementType,
  file: any
): Promise<ApiType> => {
  try {
    // gestion des datas du formulaire en y incluant le fichier si besoin
    const form = new FormData();
    form.append('data', JSON.stringify(data));
    if (typeof file != 'undefined') form.append('image', file[0]);
    //
    // appel à l'PI insertion en base
    const token = getToken();
    const request = await api.post('/appli/saveEvenement', form, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: 'Bearer ' + token,
      },
    });

    const response = await request.data;
    return response;
  } catch (error: any) {
    console.log('error', error);
    return {
      message: 'error api /appli/saveEvenement' + error.message,
      status: false,
    };
  }
};
export const getPlanning = async (data: {
  evenementId: number;
}): Promise<ApiType> => {
  const request = await fetch(K_UrlApi + 'appli/getPlanning', {
    headers: getAuthToken(),
    method: 'POST',
    body: JSON.stringify(data),
  });
  const response = await request.json();
  return response;
};

export const getProgrammation = async (data: {
  evenementId: number;
}): Promise<ApiType> => {
  const request = await api.post('appli/getProgrammation', {
    headers: getAuthToken(),
    method: 'POST',
    evenementId: data.evenementId,
  });
  const response = await request.data;
  return response;
};

export const saveProgrammation = async (
  data: ProgrammationType
): Promise<ApiType> => {
  const request = await fetch(K_UrlApi + 'appli/saveProgrammation', {
    headers: getAuthToken(),
    method: 'POST',
    body: JSON.stringify(data),
  });
  const response = await request.json();
  return response;
};

export const getCategorie = async (data: object): Promise<ApiType> => {
  const request = await fetch(K_UrlApi + 'appli/getCategorie', {
    headers: getAuthToken(),
    method: 'POST',
    body: JSON.stringify(data),
  });
  const response = await request.json();
  return response;
};*/
