export default {
    account: "Profil",
    gameBehavior: "Préférence de jeu",
    template: "Affichage",
    accountSettings: {
      name: {
        label: "Changer de nom",
        caption: "Your name may appear around ChessMate. You can remove it at any time."
      },
      delete: {
        label: 'Supprimer le @:account',
        button: 'supprimer mon @:account',
        caption: "Once you delete your account, there is no going back. Please be certain."
      }
    },
    gameBehaviorSettings: {
      display: {
        title: "Affichage",
        animation: {
          label: "Temps d'animation des pièces",
          caption: "Vitesse de réponse de l'opposant",
          choice: {
            slow: 'Lent',
            normal: 'Normal',
            fast: 'rapide'
          }
        }
      }
    },
    templateSettings: {
        title: "Thèmes",
        label: "Choix du thème",
        caption: "Once you delete your account, there is no going back. Please be certain."
    }
}
