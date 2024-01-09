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
                    <ion-label position="stacked">Gebruiksnaam</ion-label>
                    <ion-input type="email" v-model="gebruiksnaam"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-label position="stacked">Password</ion-label>
                    <ion-input type="password" v-model="password"></ion-input>
                </ion-item>
                <ion-button @click="handleLogin" expand="block" class="ion-margin-top">Login</ion-button>
            </div>
        </ion-content>
    </ion-page>
</template>
  
<script>
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonItem, IonLabel, IonInput, IonButton } from '@ionic/vue';
</script>