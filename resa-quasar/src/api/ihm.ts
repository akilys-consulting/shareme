import { api } from 'boot/axios';
import { K_storageImageEvent } from 'src/utils/config';
import { type ApiType } from 'src/api/api_types';
import { type imageUpload } from 'scr/types/ihm';

import { getToken } from 'src/utils/cookie';

function getAuthToken() {
  const token = getToken;
  return {
    Authorization: 'Bearer ' + token,
  };
}

export const copieImage = async (image: imageUpload): Promise<ApiType> => {
  const formdata = new URLSearchParams();
  const header = getAuthToken();
  console.log('image', image, header);

  formdata.append('image', JSON.stringify(image));
  formdata.append('repertoireImg', K_storageImageEvent);
  formdata.append('action', 'add');
  await api.post('/ihm/copieImage', {
    body: formdata,
  });

  return { status: true, message: 'test' };
};

export const supprimeImage = async (image: imageUpload): Promise<ApiType> => {
  const formdata = new URLSearchParams();
  const header = getAuthToken();
  console.log('image', image, header);

  formdata.append('image', JSON.stringify(image));
  formdata.append('repertoireImg', K_storageImageEvent);
  formdata.append('action', 'del');
  const request = await api.post('ihm/copieImage', {
    method: 'POST',
    body: formdata,
  });
  const response = await request.data();
  return { status: true, message: 'test' };
};
