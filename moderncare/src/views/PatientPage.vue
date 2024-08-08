<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Patiënten Beheer</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      <ion-card>
        <ion-card-header>
          <ion-card-title>Voeg Patiënt Toe</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-item>
            <ion-input label="Voornaam" v-model="voornaam"></ion-input>
          </ion-item>
          <ion-item>
            <ion-input label="Achternaam" v-model="achternaam"></ion-input>
          </ion-item>
          <ion-item>
            <ion-input label="Leeftijd" type="number" v-model="leeftijd"></ion-input>
          </ion-item>
          <ion-item>
            <ion-select label="Geslacht" v-model="geslacht">
              <ion-select-option value="Man">Man</ion-select-option>
              <ion-select-option value="Vrouw">Vrouw</ion-select-option>
            </ion-select>
          </ion-item>
          <ion-item>
            <ion-datetime label="Opname Datum" display-format="YYYY-MM-DD" v-model="opnameDatum"></ion-datetime>
          </ion-item>
          <ion-item>
            <ion-datetime label="Ontslag Datum" display-format="YYYY-MM-DD" v-model="ontslagDatum"></ion-datetime>
          </ion-item>
          <ion-item>
            <ion-select label="Blok Naam" v-model="blokNaam" @ionChange="updateVerdiepingen">
              <ion-select-option v-for="blok in uniqueBlokNamen" :key="blok" :value="blok">{{ blok
                }}</ion-select-option>
            </ion-select>
          </ion-item>
          <ion-item>
            <ion-select label="Verdieping" v-model="verdieping" @ionChange="updateKamerNummers">
              <ion-select-option v-for="verdieping in uniqueVerdiepingen" :key="verdieping" :value="verdieping">{{
                verdieping }}</ion-select-option>
            </ion-select>
          </ion-item>
          <ion-item>
            <ion-select label="Kamer Nummer" v-model="kamerNummer">
              <ion-select-option v-for="kamer in filteredKamers" :key="kamer.KamerNummer" :value="kamer.KamerNummer">{{
                kamer.KamerNummer }}</ion-select-option>
            </ion-select>
          </ion-item>
          <ion-button expand="full" @click="addPatient">Voeg Patiënt Toe</ion-button>
        </ion-card-content>
      </ion-card>

      <ion-list>
        <ion-item v-for="patient in patients" :key="patient.PatiëntID">
          <ion-label>
            <h2>{{ patient.Voornaam }} {{ patient.Achternaam }}</h2>
            <p>{{ patient.Geslacht }}, {{ patient.Leeftijd }} jaar</p>
          </ion-label>
          <ion-button color="danger" @click="deletePatient(patient.PatiëntID)">Verwijder</ion-button>
        </ion-item>
      </ion-list>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import {
  IonPage,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonContent,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardContent,
  IonItem,
  IonLabel,
  IonInput,
  IonDatetime,
  IonSelect,
  IonSelectOption,
  IonButton,
  IonList
} from '@ionic/vue';

const patients = ref([]);
const voornaam = ref('');
const achternaam = ref('');
const leeftijd = ref('');
const geslacht = ref('');
const opnameDatum = ref('');
const ontslagDatum = ref('');
const blokNaam = ref('');
const kamerNummer = ref('');
const verdieping = ref('')

const kamers = ref([]);
const uniqueBlokNamen = ref([]);
const uniqueVerdiepingen = ref([]);
const filteredKamers = ref([]);

const fetchPatients = () => {
  axios.get('https://gauravghimire.be/API_modernCare/api/PatientGet.php')
    .then(response => {
      patients.value = response.data.data;
    })
    .catch(error => {
      console.error('Error fetching patients:', error);
    });
};

const fetchKamers = () => {
  axios.get('https://gauravghimire.be/API_modernCare/api/KamerGet.php')
    .then(response => {
      kamers.value = response.data.data;
      updateBlokNamen();
    })
    .catch(error => {
      console.error('Error fetching kamers:', error);
    });
};

const updateBlokNamen = () => {
  const blokNamen = kamers.value.map(kamer => kamer.BlokNaam);
  uniqueBlokNamen.value = [...new Set(blokNamen)];
};

const updateVerdiepingen = () => {
  const verdiepingen = kamers.value
    .filter(kamer => kamer.BlokNaam === blokNaam.value)
    .map(kamer => kamer.Verdieping);
  uniqueVerdiepingen.value = [...new Set(verdiepingen)];
  updateKamerNummers(); 
};

const updateKamerNummers = () => {
  filteredKamers.value = kamers.value.filter(kamer => kamer.BlokNaam === blokNaam.value && kamer.Verdieping === verdieping.value);
};

const addPatient = () => {
  const formattedOpnameDatum = new Date(opnameDatum.value).toISOString();
  const formattedOntslagDatum = new Date(ontslagDatum.value).toISOString();

  // Fetch KamerID aan de hand van BlokNaam, KamerNummer, and Verdieping
  const kamer = kamers.value.find(k => k.BlokNaam === blokNaam.value && k.KamerNummer === kamerNummer.value && k.Verdieping === verdieping.value);
  if (kamer) {
    const KamerID = kamer.KamerID;
    // Add Patient 
    axios.post('https://gauravghimire.be/API_modernCare/api/PatientAdd.php', {
      Voornaam: voornaam.value,
      Achternaam: achternaam.value,
      Leeftijd: leeftijd.value,
      Geslacht: geslacht.value,
      OpnameDatum: formattedOpnameDatum,
      OntslagDatum: formattedOntslagDatum,
      KamerID: KamerID
    })
      .then(response => {
        if (response.data.status === 'ok') {
          fetchPatients();
          clearForm();
        } else {
          console.error('Error adding patient:', response.data);
        }
      })
      .catch(error => {
        console.error('Error adding patient:', error);
      });
  } else {
    console.error('Error finding KamerID');
  }
};

const deletePatient = (patientID) => {
  axios.post('https://gauravghimire.be/API_modernCare/api/PatientDelete.php', { PatiëntID: patientID })
    .then(response => {
      if (response.data.status === 'ok') {
        fetchPatients();
      } else {
        console.error('Error deleting patient:', response.data);
      }
    })
    .catch(error => {
      console.error('Error deleting patient:', error);
    });
};

const clearForm = () => {
  voornaam.value = '';
  achternaam.value = '';
  leeftijd.value = '';
  geslacht.value = '';
  opnameDatum.value = '';
  ontslagDatum.value = '';
  blokNaam.value = '';
  kamerNummer.value = '';
  verdieping.value = '';
};

onMounted(() => {
  fetchPatients();
  fetchKamers();
});

watch(blokNaam, () => {
  updateVerdiepingen();
});

watch(verdieping, () => {
  updateKamerNummers();
});
</script>

<style scoped>
.ion-padding {
  padding: 16px;
}
</style>