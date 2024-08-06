<template>
  <ion-page>
      <ion-header>
          <ion-toolbar>
              <ion-title>Lijsten</ion-title>
          </ion-toolbar>
      </ion-header>

      <ion-content class="ion-padding">
          <ion-list>
              <ion-item v-for="(item, index) in lijsten" :key="index">
                  <ion-label>
                      <h2>Kamer Nummer: {{ item.KamerNummer }}</h2>
                      <p>Prioriteit: {{ item.Prioriteit }}</p>
                      <p>Datum: {{ item.DatumTijdNotitie }}</p>
                  </ion-label>
                  <ion-button @click="WorkDone(item, index)" style="justify-content: center;">
                      <ion-icon slot="icon-only" :icon="checkmarkOutline"></ion-icon>
                  </ion-button>
                  <ion-button @click="WorkInProgress(item)" style="justify-content: center;">
                      <ion-icon slot="icon-only" :icon="syncOutline"></ion-icon>
                  </ion-button>
                  <ion-button @click="showDetails(item)">
                      <ion-icon slot="start" :icon="informationCircleOutline"></ion-icon>
                      Info
                  </ion-button>
              </ion-item>
          </ion-list>
      </ion-content>
      <ion-modal v-if="selectedItem" :is-open="isModalOpen" :backdropDismiss="false">
          <ion-content>
              <ion-card>
                  <ion-card-header>
                      <ion-card-title>Verpleegster Info</ion-card-title>
                  </ion-card-header>
                  <ion-card-content>
                      <p>Verpleegster Voornaam: {{ selectedItem.VerpleegsterVoornaam }}</p>
                      <p>Verpleegster Achternaam: {{ selectedItem.VerpleegsterAchternaam }}</p>
                      <p>Specialiteit: {{ selectedItem.Specialiteit }}</p>
                  </ion-card-content>
              </ion-card>
              <ion-card>
                  <ion-card-header>
                      <ion-card-title>Patient Info</ion-card-title>
                  </ion-card-header>
                  <ion-card-content>
                      <p>Patient Voornaam: {{ selectedItem.PatientVoornaam }}</p>
                      <p>Patient Achternaam: {{ selectedItem.PatientAchternaam }}</p>
                      <p>Leeftijd: {{ selectedItem.Leeftijd }}</p>
                      <p>Geslacht: {{ selectedItem.Geslacht }}</p>
                      <p>Opname Datum: {{ selectedItem.OpnameDatum }}</p>
                      <p>Ontslag Datum: {{ selectedItem.OntslagDatum }}</p>
                  </ion-card-content>
              </ion-card>
              <ion-button @click="closeModal">
                  Close
              </ion-button>
          </ion-content>
      </ion-modal>
  </ion-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { informationCircleOutline, syncOutline, checkmarkOutline } from 'ionicons/icons';
import {
  IonPage,
  IonContent,
  IonList,
  IonItem,
  IonLabel,
  IonButton,
  IonModal,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardContent,
  IonIcon,
  IonHeader, 
  IonTitle,
  IonToolbar
} from '@ionic/vue';

const lijsten = ref([]);
const selectedItem = ref(null);
const isModalOpen = ref(false);

const fetchDetails = () => {
  axios
    .post('https://gauravghimire.be/API_modernCare/api/GetDetails.php')
    .then((response) => {
      if (response.status !== 200) {
        console.log(response.status);
      }
      if (!response.data.data) {
        console.log('response.data.data is not ok');
        return;
      }
      console.log(response.data);

      lijsten.value = response.data.data;
    })
    .catch((error) => {
      console.log('API call error:', error);
    });
};

const showDetails = (item) => {
  selectedItem.value = item;
  isModalOpen.value = true;
};

const closeModal = () => {
  selectedItem.value = null;
  isModalOpen.value = false;
};

const WorkDone = (item, index) => {
  axios
    .post('https://gauravghimire.be/API_modernCare/api/RemoveOngevalType.php', {
      ID: item.ID
    })
    .then((response) => {
      console.log('API Response:', response.data);
      if (response.data.status === "ok") {
        lijsten.value.splice(index, 1);
        console.log('Item removed successfully');
      } else {
        console.log('Error deleting item:', response.data);
      }
    })
    .catch((error) => {
      console.log('Error:', error.response ? error.response.data : error.message);
    });
};

onMounted(() => {
  fetchDetails();
});
</script>

