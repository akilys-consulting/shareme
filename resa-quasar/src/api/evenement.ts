import { api } from 'boot/axios';
import { type ApiType } from 'src/api/api_types';
import { getFilterEVt } from 'src/utils/cookie';
import { API_EvenementType, EvenementType } from 'src/types/evenements';

//
// permet de récupérer tous les enregistrements evènments
export const listEvenements = async (): Promise<ApiType> => {
  const filtre = getFilterEVt();
  const allEvenements: EvenementType[] = [];

  try {
    const response = await api.post('/getAllEvt', {
      filtre: filtre,
    });

    if (response.status === 200) {
      // lecture du retour

      //
      // on parse les colonnes JSON
      response.data.evenements.forEach((data: API_EvenementType) => {
        // gestion des colonnes standards
        allEvenements.push({
          id: data.id,
          titre: data.titre,
          description: data.description,
          url: data.url,
          actif: data.actif,
          event_id: data.event_id,
          image: data.image,
          nb_personnes: data.nb_personnes,
          auteur: JSON.parse(data.auteur),
          adresse: JSON.parse(data.adresse),
          categories: JSON.parse(data.categories),
          recurrence: JSON.parse(data.recurrence),
        });
      });
      return {
        status: true,
        data: allEvenements,
        message: 'list evet ok',
      };
    } else {
      return {
        status: false,
        data: [{}],
        message: 'code retour différent de 200',
      };
    }
  } catch (error) {
    return { status: false, data: [{}], message: (error as Error).message };
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
