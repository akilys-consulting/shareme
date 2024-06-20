import { api } from 'boot/axios';
import { type ApiType } from 'src/api/api_types';

export const copieImage = async (image: string): Promise<ApiType> => {
  try {
    const response = await api.post('/upload', {
      image: image,
    });
    return {
      status: true,
      data: [{ fichier: response.data.fichier }],
      message: 'fichier copié',
    };
  } catch (error) {
    return { status: false, data: [{}], message: (error as Error).message };
  }
};
