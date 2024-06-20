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

//
// mise à jour d 'un évènement
export const saveEvenement = async (
  dataEvt: EvenementType
): Promise<ApiType> => {
  try {
    const response = <EvenementType>await api.post('/miseAJourEvenement', {
      data: dataEvt,
    });
    return {
      status: true,
      data: response,
      message: 'mise à jour effectuée',
    };
  } catch (error) {
    return { status: false, data: [{}], message: (error as Error).message };
  }
};
