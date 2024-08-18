<template>
    <ion-page>
      <ion-header>
        <ion-toolbar>
          <ion-title class="ion-text-center">Over deze App</ion-title>
        </ion-toolbar>
      </ion-header>
  
      <ion-content class="ion-padding full-height">
        <ion-card>
          <ion-card-header>
            <ion-card-title class="large-text">Wat is deze App?</ion-card-title>
          </ion-card-header>
          <ion-card-content>
            <p class="large-text">
              Deze app is speciaal ontworpen voor bejaardentehuizen om patiënten in staat te stellen gemakkelijk hulp te vragen van verpleegsters tijdens hun verblijf. Verpleegsters kunnen direct zien waar hulp nodig is, gerangschikt op basis van de ernst van de gevallen. Dit helpt om snel en efficiënt zorg te bieden aan de bewoners, waardoor hun welzijn wordt verbeterd.
            </p>
          </ion-card-content>
        </ion-card>
  
        <ion-card>
          <ion-card-header>
            <ion-card-title class="large-text">Over het Team</ion-card-title>
          </ion-card-header>
          <ion-card-content>
            <ion-grid>
              <ion-row class="team-row">
                <ion-col size="12" size-md="4" class="ion-text-center team-member">
                  <ion-img :src="prashantImg" alt="Foto van Prashant" class="large-img"></ion-img>
                  <p class="large-text name-text">Prashant</p>
                </ion-col>
                <ion-col size="12" size-md="4" class="ion-text-center team-member">
                  <ion-img :src="manojImg" alt="Foto van Manoj" class="large-img"></ion-img>
                  <p class="large-text name-text">Manoj</p>
                </ion-col>
                <ion-col size="12" size-md="4" class="ion-text-center team-member">
                  <ion-img :src="gauravImg" alt="Foto van Gaurav" class="large-img"></ion-img>
                  <p class="large-text name-text">Gaurav</p>
                </ion-col>
              </ion-row>
            </ion-grid>
          </ion-card-content>
        </ion-card>
      </ion-content>
    </ion-page>
  </template>
  
  <script setup>
  import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonGrid, IonRow, IonCol, IonImg } from '@ionic/vue';
  
  const prashantImg = 'https://via.placeholder.com/200';
  const manojImg = 'https://via.placeholder.com/200';
  const gauravImg = 'https://via.placeholder.com/200';
  </script>
  
  <style scoped>
  .full-height {
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  
  .ion-card {
    flex-grow: 1;
    margin: 10px;
  }
  
  .large-text {
    font-size: 1.4em;
  }
  
  .large-img {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    margin-bottom: 10px;
  }
  
  .team-member {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  
  .team-row {
    flex-direction: column;
  }
  
  @media (min-width: 768px) {
    .team-row {
      flex-direction: row;
      justify-content: space-around;
    }
  }
  
  .name-text {
    font-size: 1.2em;
    margin-top: 10px;
  }
  </style>
  