export const K_storageToken = 'appisToken';
export const K_storageUser = 'appisUser';
export const K_UrlApi = '/api/';

export const K_storageImageEvent = 'images/evenements/';
export const K_storageAvatarSociete = 'images/avatar/';
export const K_storageAvatarUser = 'images/avatar/';
export const K_imgDefaut = 'imageParDefaut.png';
export const K_resize_img_width = 320;
export const K_resize_img_height = 230;
export const K_size_avatar = '300000';

export const K_InitMdp = 'resaAll@2023';

export const K_formatDateBase = 'YYYY-MM-DD HH:mm';
export const K_formatDateAffiche = 'DD/MM/YYYY';

export const checkRules = {
  required: (value: string) => !!value || 'champ obligatoire.',
  password: (value: string) => value.length > 6 || '6 carcatères min ',
  email: (value: string) => {
    const pattern =
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pattern.test(value) || "ce n'est pas un e-mail";
  },
  telephone: (value: string) => {
    const pattern = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
    return pattern.test(value) || "ce n'est pas numéro de téléphone";
  },
};
