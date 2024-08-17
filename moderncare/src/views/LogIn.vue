<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-title>Login</ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
            <div class="login-container">
                <ion-item>
                    <ion-input type="email" v-model="username" placeholder="Username" aria-label="Username"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-input type="password" v-model="wachtwoord" placeholder="Password"
                        aria-label="Password"></ion-input>
                </ion-item>
                <ion-button type="submit" @click="handleLogin" expand="block" class="ion-margin-top">Login</ion-button>
            </div>
        </ion-content>
    </ion-page>
</template>
  
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonItem, IonLabel, IonInput, IonButton} from '@ionic/vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const username = ref('Admin');
const wachtwoord = ref('admin');

const handleLogin = () => {
    console.log('Attempting login with:', username.value, wachtwoord.value);
    axios.post('https://gauravghimire.be/API_modernCare/api/UserGet.php', {
        username: username.value,
        wachtwoord: wachtwoord.value
    }, {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    })
        .then(response => {
            console.log('Response received:', response.data);
            if (response.data.status === 'ok') {
                console.log('Login successful:', response.data.message);
                console.log('User data to store:', response.data.user);  // Check what you're actually storing

                localStorage.setItem('userData', JSON.stringify(response.data.user));
                router.push('/tabs/tabLijsten'); 
               
            setTimeout(() => {
                window.location.reload();
            }, 100); 
            } else {
                console.log('Login failed:', response.data.error);
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            window.alert('Er is een fout opgetreden tijdens het inloggen.');
        });
};
</script>