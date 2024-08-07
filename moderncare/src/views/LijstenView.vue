<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Lijsten</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      <ion-list>
        <ion-card
          v-for="(item, index) in lijsten"
          :key="index"
          :class="{'red-border': item.Status == 0, 'green-border': item.Status == 1}"
        >
          <ion-card-header>
            <ion-card-title>Kamer Nummer: {{ item.KamerNummer }}</ion-card-title>
          </ion-card-header>
          <ion-card-content>
            <p>Prioriteit: {{ item.Prioriteit }}</p>
            <p>Tijd: {{ item.Time }}</p>
            <div class="button-container">
              <ion-button @click="WorkDone(item, index)" style="justify-content: center;">
                <ion-icon slot="icon-only" :icon="checkmarkOutline"></ion-icon>
              </ion-button>
              <ion-button @click="WorkInProgress(item, index)" style="justify-content: center;">
                <ion-icon slot="icon-only" :icon="syncOutline"></ion-icon>
              </ion-button>
              <ion-button @click="showDetails(item)">
                <ion-icon slot="start" :icon="informationCircleOutline"></ion-icon>
                Info
              </ion-button>
            </div>
          </ion-card-content>
        </ion-card>
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
            <p>Gelsacht: {{ selectedItem.Geslacht }}</p>
            <p>Opname Datum: {{ selectedItem.OpnameDatum }}</p>
            <p>Ontsag Datum: {{ selectedItem.OntslagDatum }}</p>
          </ion-card-content>
          <ion-button @click="closeModal">Close</ion-button>
        </ion-card>
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
  axios.post('https://gauravghimire.be/API_modernCare/api/GetDetails.php')
    .then((response) => {
      if (response.status !== 200) {
        console.log(response.status);
      }
      if (!response.data.data) {
        console.log('response.data.data is not ok');
        return;
      }
      console.log(response.data);

      lijsten.value = response.data.data.map(item => ({
        ...item,
        Status: item.Status || 0  
      }));
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
  console.log(item.VerzoekID);
  console.log(item.Status);
  console.log(item.OngevalID);
  
  item.Status = 0;
  
  axios.post('https://gauravghimire.be/API_modernCare/api/UpdateStatus.php', {
      ID: item.VerzoekID,
      Status: item.Status,
      OngevalID: item.OngevalID
  })
  .then((response) => {
      console.log('API Response:', response.data);
      if (response.data.status === "ok") {
          lijsten.value.splice(index, 1); // Remove item from the list
          console.log('Item removed successfully');
      } else {
          console.log('Error updating item:', response.data);
      }
  })
  .catch((error) => {
      console.log('Error:', error.response ? error.response.data : error.message);
  });
};


const WorkInProgress = (item) => {
  console.log(item.OngevalID);
  console.log(item.KamerID);
  console.log(item.Status);
  console.log(item.Beschrijving);

    if(item.Status == 1){
      return;
    }else{
    item.Status = 1;
    axios.post('https://gauravghimire.be/API_modernCare/api/VerzoekNotitiesAdd.php', {
      KamerID: item.KamerID,
      Status: item.Status,
      OngevalID: item.OngevalID,  // Include OngevalID
    })
      .then((response) => {
        console.log('VerzoekNotatie added:', response.data);
        if (response.data.status !== "ok") {
          console.log('Error adding VerzoekNotatie:', response.data);
        }
      })
      .catch((error) => {
        console.log('Error:', error.response ? error.response.data : error.message);
      });
    }
  };

onMounted(() => {
  fetchDetails();
});
</script>


<style scoped>
.red-border {
  border: 2px solid red;
}

.green-border {
  border: 2px solid green;
}

.button-container {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
</style>
