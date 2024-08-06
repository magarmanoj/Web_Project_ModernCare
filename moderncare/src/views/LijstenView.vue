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
    <ion-modal v-if="selectedItem" :is-open="isModalOpen">
      <ion-content>
        <ion-card>
          <ion-card-header>
            <ion-card-title>Verpleegster Info</ion-card-title>
          </ion-card-header>
          <ion-card-content>
            <p>Verpleegster Voornaam: {{ selectedItem.VerpleegsterVoornaam }}</p>
            <p>Verpleegster Achternaam: {{ selectedItem.VerpleegsterAchternaam }}</p>
            <p>Specialiteit: {{ selectedItem.Specialiteit }}</p>
            <p>NoodVerzoek Status: {{ selectedItem.NoodVerzoekStatus }}</p>
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
            <p>Gelsacht: {{ selectedItem.Geslacht }}</p>
            <p>Opname Datum: {{ selectedItem.OpnameDatum }}</p>
            <p>Ontsag Datum: {{ selectedItem.OntslagDatum }}</p>
          </ion-card-content>

          <ion-button @click="closeModal">
            Close
          </ion-button>
        </ion-card>
      </ion-content>
    </ion-modal>
  </ion-page>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { informationCircleOutline, syncOutline,checkmarkOutline  } from 'ionicons/icons';
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

const fetchDetails= () => {
axios
  .post('https://gauravghimire.be/API_modernCare/api/GetDetails.php')
  .then((response) => {
    // controleer de response
    if (response.status !== 200) {
      // er is iets fout gegaan, doe iets met deze info
      console.log(response.status);
    }
    if (!response.data.data) {
      // De data die we verwachten zit niet in response.data :
      // de aangesproken API stopt zijn data in een data object (eigen code).
      // Deze data zit echter ook in het data object in response.
      // Daarom dus response.data.data
      console.log('response.data.data is not ok');
      return;
    }
    console.log(response.data);

    lijsten.value = [];
    for (let i = 0, end = response.data.data.length; i < end; i++) {
      lijsten.value.push(response.data.data[i]);
    }
  });
};

const showDetails = (item) => {
selectedItem.value = item;
isModalOpen.value = true;
};

const closeModal = () => {
selectedItem.value = null;
isModalOpen.value = false;
}

const WorkDone = (item, index) => {
  // Log the parameters to verify
  console.log('Item to be deleted:', item);
  console.log('Index in list:', index);

  // Perform the API request
  axios
    .post('https://gauravghimire.be/API_modernCare/api/RemoveOngevalType.php', {
      PatiëntID: item.PatiëntID,  // Ensure this is the correct field
      ID: item.ID // Ensure this is the correct field
    })
    .then((response) => {
      console.log('API Response:', response.data); // Log the entire response
      if (response.data.status === "ok") {
        // Optionally remove the item from the list
        lijsten.value.splice(index, 1);
        console.log('Item removed successfully');
      } else {
        // Log the whole response to debug
        console.log('Error deleting item:', response.data);
      }
    })
    .catch((error) => {
      console.log('Error:', error.response ? error.response.data : error.message);
    });
};





onMounted(() => {
  fetchDetails()
});

</script>