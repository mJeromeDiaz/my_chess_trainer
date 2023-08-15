import { defineStore } from 'pinia';
import axios from 'axios'

function parseJwt (token) {
  let base64Url = token.split('.')[1];
  let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
  let jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
      return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
  }).join(''));

  return JSON.parse(jsonPayload);
}


export const securityStore = defineStore('security', {
  state: () => {
    return {
      user: { roles:[] },
      preferences: { waintingTime: 200 },
      token:  '',
    }
  },
  actions: {
    isAdmin(){
      return this.user.roles[0] === 'ROLE_ADMIN'
    },
    logout(){
      axios({
        method: 'GET',
        url: 'sec/logout',
        headers: { 'Content-Type': 'application/json' }
      })
      .then(() => {
        this.user = {}
        this.token = ''
      })
    }
  },

})
