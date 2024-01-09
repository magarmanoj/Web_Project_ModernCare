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
import { ref } from 'vue';
import axios from 'axios';
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonGrid, IonRow, IonCol, IonButton } from '@ionic/vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';

library.add(fas);

// Define the method to update the PHP server
const updateOngevalType = async (prioriteit) => {
  try {
    const response = await axios.post('https://gauravghimire.be/API/API_moderneCare/api/OngevalType.php', {
      prioriteit: prioriteit
    });
    // Handle response here
    console.log(response.data);
  } catch (error) {
    // Handle error here
    console.error(error);
  }
};
</script>
  

<style>
.homeContent {
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
}

.homeGrid {
    height: 100%;
}

.homeRow {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.homeCol {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.oproepBtn {
    width: 100%;
    height: 100%;
    --padding-start: 0;
    --padding-end: 0;
    --padding-top: 0;
    --padding-bottom: 0;
    margin: 16px 0;
}

ion-button {
    --min-height: 100%;
    height: 100%;
}
</style>