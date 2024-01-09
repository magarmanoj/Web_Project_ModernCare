<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-title class="ion-text-center">Home</ion-title>
            </ion-toolbar>
        </ion-header>

        <ion-content class="homeContent">
            <ion-grid class="homeGrid">
                <ion-row>
                    <ion-col class="ion-text-center">
                        <ion-button @click="updateOngevalType('Laag')" class="oproepBtn" expand="full">
                            <font-awesome-icon :icon="['fas', 'restroom']" />
                        </ion-button>
                    </ion-col>
                    <ion-col class="ion-text-center">
                        <ion-button @click="updateOngevalType('Hoog')" class="oproepBtn" expand="full">
                            <font-awesome-icon :icon="['fas', 'circle-exclamation']" />
                        </ion-button>
                    </ion-col>
                    <ion-col class="ion-text-center">
                        <ion-button @click="updateOngevalType('Middel')" class="oproepBtn" expand="full">
                            <font-awesome-icon :icon="['fas', 'person-falling-burst']" />
                        </ion-button>
                    </ion-col>
                </ion-row>
            </ion-grid>
        </ion-content>
    </ion-page>
</template>
  
<script setup>
import axios from 'axios';
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonGrid, IonRow, IonCol, IonButton } from '@ionic/vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';

library.add(fas);

// Define the method to update the PHP server
const updateOngevalType = (prioriteit) => {
    axios
        .post('https://gauravghimire.be/API_modernCare/api/OngevalType.php', {
            Prioriteit: prioriteit
        })
        .then(response => response.data)
        .then(responseData => {
            if (responseData.status == 'ok') {
                console.log(responseData.data);
            }
        })
        .catch(error => {
            console.error('Error saving project:', error);
            window.alert('Er is een fout opgetreden bij het opslaan van het project.');
        });
}
</script>
  