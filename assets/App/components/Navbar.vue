<template>
    <div>
        <nav class="relative flex justify-between p-4">
            <img class="inline-block" :src="require('../../img/smallLogo.svg').default">
            <div v-if="isLogged" class="inline-block nav__menu">
                <div class="flex items-center space-x-4">
                    <div class="font-bold text-purple-500">
                        {{name}}
                    </div>
                    <Button v-on:click="logout" text="Déconnexion" :color="isAdmin ? 'pink-600' : 'black'"></Button>
                </div>
            </div>
            <div v-if="!isLogged" class="inline-block nav__menu">
                <div>
                    <Button v-on:click="popupType = 'signup'" class="mr-4" text="Inscription" color="black"></Button>
                    <Button v-on:click="popupType = 'login'" reversed=true text="Connexion" color="black"></Button>
                </div>
                <div class="absolute right-0 transform translate-y-full right-6 -bottom-8">
                    <transition name="appear" mode="out-in">
                        <Popup v-on:login="popupType = ''; isLogged = true" route="/authentication_token" v-if="popupType == 'login'" submit="login" cta="Se connecter" ctaIcon="sign-in-alt" :fields="[
                            {type: 'mail', placeholder: 'Addresse mail', name: 'email'},
                            {type: 'password', placeholder: 'Mot de passe', name: 'password'},
                        ]">
                        </Popup>
                        <Popup v-on:close="popupType = ''" v-else-if="popupType == 'signup'" route="/register" cta="S'inscrire" submit="signup" ctaIcon="user-plus" :fields="[
                            {type: 'text', placeholder: 'Nom', name: 'nom'},
                            {type: 'text', placeholder: 'Prénom', name: 'prenom'},
                            {type: 'mail', placeholder: 'Addresse mail', name: 'email'},
                            {type: 'password', placeholder: 'Mot de passe', name: 'password'},
                        ]">
                        </Popup>
                    </transition>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import Button from './Button.vue'
import Popup from './Popup.vue'
export default {
  components: { Button, Popup },
  data() {
      return {
        Name: '',
        popupType: '',
        isLogged: false,
        isAdmin: false,
      }
  },
  mounted() {
    let token = localStorage.getItem('token');
    if(token) {
        this.isLogged = true;
    }
    let admin = localStorage.getItem('admin');
    if(admin) {
        this.isAdmin = true;
    }

    let name = localStorage.getItem('name');
    if(name) {
        this.name = name;
    }
    window.emitter.on('admin', () => {
        this.isAdmin = true;
    } )   

    window.emitter.on('name', (name) => {
        this.name = name;
    } )   
  },
  methods: {
    logout: function() {
        window.alert('Déconnexion réussie !');
        localStorage.removeItem('token');
        localStorage.removeItem('admin');
        localStorage.removeItem('name');   
        this.isAdmin = false;
        this.isLogged = false;
        this.name = '';
    }
  }
}
</script>

<style scoped>
    .appear-enter-active, .appear-leave-active {
        transition: opacity .3s ease;
    }
    .appear-enter-from, .appear-leave-to {
        opacity: 0;
    }
</style>