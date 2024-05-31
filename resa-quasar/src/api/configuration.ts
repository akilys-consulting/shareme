import { api } from 'boot/axios';
import { type ApiType } from 'src/api/api_types';
import { type SocieteType } from 'src/types/configuration';
import { type UserType } from 'src/types/users';
import { getToken } from 'src/utils/cookie';

function getAuthToken() {
  const token = getToken();
  return {
    Authorization: 'Bearer ' + token,
  };
}
//
// permet de récupérer tous les enregistrements evènments
// TODO : filtrer sur un commercial et une année
export const getSocietes = async (): Promise<ApiType> => {
  const request = await api.get('/configuration/listSociete', {
    headers: getAuthToken(),
  });
  const response = await request.data;
  return response;
};

export const updateSociete = async (
  data: SocieteType[],
  file: any
): Promise<ApiType> => {
  const token = getToken();

  const form = new FormData();
  form.append('data', JSON.stringify(data));
  if (typeof file != 'undefined') form.append('image', file[0]);

  const request = await api.post('/configuration/updateSociete', form, {
    headers: {
      'Content-Type': 'multipart/form-data',
      Authorization: 'Bearer ' + token,
    },
  });
  const response = await request.data;
  return response;
};

export const delSociete = async (data: object): Promise<ApiType> => {
  const request = await api({
    method: 'post',
    url: '/configuration/delSociete',
    headers: getAuthToken(),
    data: JSON.stringify(data),
  });

  const response = await request.data;
  return response;
};

export const getUsers = async (data: object): Promise<ApiType> => {
  const request = await api.post('/configuration/listUser', {
    headers: getAuthToken(),
    body: JSON.stringify(data),
  });
  const response = await request.data();
  return response;
};

export const setDataUser = async (
  data: UserType,
  file: any
): Promise<ApiType> => {
  try {
    // gestion des datas du formulaire en y incluant le fichier si besoin
    const form = new FormData();
    form.append('data', JSON.stringify(data));
    if (typeof file != 'undefined') form.append('image', file[0]);
    const token = getToken();
    const request = await api.post('/configuration/setUser', form, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: 'Bearer ' + token,
      },
    });
    const response = await request.data;
    return response;
  } catch (error: any) {
    return {
      message: 'error api setDataUser ' + error.message,
      status: false,
    };
  }
};

export const delUser = async (data: object): Promise<ApiType> => {
  const request = await api.post('/configuration/delUser', {
    headers: getAuthToken(),
    body: JSON.stringify(data),
  });
  const response = await request.data();
  return response;
};

export const getSocieteUser = async (data: any) => {
  const request = await api.post('/configuration/getUserInfo', {
    method: 'POST',
    headers: getAuthToken(),
    body: JSON.stringify(data),
  });
  const response = await request.data();
  return response;
};

export const updateMdp = async (data: UserType): Promise<ApiType> => {
  const token = getToken();
  const form = new FormData();
  form.append('data', JSON.stringify(data));
  const request = await api.post('/users/updateMdp', form, {
    headers: { Authorization: 'Bearer ' + token },
  });
  const response: ApiType = await request.data;
  return response;
};
