import { boot } from "quasar/wrappers";
import axios from "axios";
import { Notify, Loading } from 'quasar';
import { securityStore } from '../stores/security.js';



/**
 * Tableau d'erreur - peut être enrichi ou modifié via i18n
 */
const traductions = {
  1: '500 - L\'API ne semble pas répondre - serveur KO',
  400: '400 - Requête erronée',
  401: '401 - Authentification invalide ou périmé',
  403: '403 - Accès refusés',
  404: '404 - Erreur, la ressource n\'a pas été trouvée',
  415: '415 - Media non supporté - Format de requête invalide',
  422: '422 - Le contenu de votre demande n\'est pas conforme',
  500: '500 - Erreur API - merci de contacter un administrateur',
  other: '500 - Une erreur c\'est produite, merci de réessayer plus tard ou contacter un administrateur'
}

/**
 * traduit les status d'erreurs axios
 * @param [object] $error > l'erreur renvoyée par axios
 * @return [string] $msg > le message d'erreur traduit
 *
 * a noter, si error.response il peut y avoir plusieurs autres méthodes appelées
 * console.error(error.response.status)
 * console.error(error.response.statusText)
 * console.error(error.response.message)
 * console.error(error.response.data)
 * console.error(error.response.headers)
 */
// REFACT trop de IF
 function translate (error) {

  if (error.response) {
    if(error.response.data.msg){
      return error.response.data.msg.replace(/\n/g, "<br />")
    }
    if(error.response.data['hydra:description']){
      return error.response.data['hydra:description'].replace(/\n/g, "<br />")
    }
    if(error.response.data.detail){
      return error.response.data.detail.replace(/\n/g, "<br />")
    }
    if (error.response.status) {
      if (error.response.statusText) {
        return traductions[error.response.status] || error.statusText
      }
      return traductions[error.response.status] || traductions.other
    }
    return traductions.other
  }
  if (error.request) {
    return traductions[1]
  }
  if (error.message) {
    return error.message
  }
  return traductions.other
}

  export default boot(({ app, router, api, urlPath  }) => {

    axios.defaults.baseURL = process.env.URL;


    axios.interceptors.request.use(async (config) => {
      const security = securityStore()

      if (security?.token && security?.user) {
          config.headers.Authorization =  'Bearer ' + security.jwt;
      }

      Loading.show()
      return config

    }, error => {
      Notify.create({ message: translate(error), color: 'negative' })
      Loading.hide()
      return Promise.reject(error)
    })

    axios.interceptors.response.use(response => {

      Loading.hide()
      if(response === undefined){
        router.push({ name: 'e404'})
      }
      return response
    }, error => {

      Loading.hide()
      Notify.create({ message: translate(error), color: 'negative' })

  })
  app.config.globalProperties.$axios = axios;
  app.config.globalProperties.$api = api;
});

