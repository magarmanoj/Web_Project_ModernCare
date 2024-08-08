<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Beheer</ion-title>
        <ion-button slot="end" @click="logout">
          <ion-icon :icon="logOutOutline"></ion-icon>
        </ion-button>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      <div class="container">
        <!-- Patient Management Section -->
        <div class="management-section">
          <ion-card>
            <ion-card-header>
              <ion-card-title>Voeg Patiënt Toe</ion-card-title>
            </ion-card-header>
            <ion-card-content>
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
          <!-- Patient list -->
        </div>

        <!-- Verpleegster Management Section -->
        <div class="management-section">
          <ion-card>
            <ion-card-header>
              <ion-card-title>Voeg Verpleegster Toe</ion-card-title>
            </ion-card-header>
            <ion-card-content>
              <ion-card>
              <ion-card-header>
                  <ion-card-title>Voeg Verpleegster Toe</ion-card-title>
              </ion-card-header>
              <ion-card-content>
                  <ion-item>
                      <ion-input label="Voornaam" v-model="verpleegstervoornaam"></ion-input>
                  </ion-item>
                  <ion-item>
                      <ion-input label="Achternaam" v-model="verpleegsterachternaam"></ion-input>
                  </ion-item>
                  <ion-item>
                      <ion-input label="Telefoon" v-model="telefoon"></ion-input>
                  </ion-item>
                  <ion-item>
                      <ion-input label="Email" v-model="email"></ion-input>
                  </ion-item>
                  <ion-item>
                      <ion-input label="Specialiteit" v-model="specialiteit"></ion-input>
                  </ion-item>
                  <ion-item>
                      <ion-input label="Username" v-model="username"></ion-input>
                  </ion-item>
                  <ion-item>
                      <ion-input label="Wachtwoord" type="password" v-model="wachtwoord"></ion-input>
                  </ion-item>
                  <ion-button expand="full" @click="addVerpleegster">Voeg Verpleegster Toe</ion-button>
              </ion-card-content>
          </ion-card>
              <!-- Verpleegster form inputs and buttons -->
            </ion-card-content>
          </ion-card>
          <ion-list>
              <ion-item v-for="verpleegster in verpleegsters" :key="verpleegster.VerpleegsterID">
                  <ion-label>
                      <h2>{{ verpleegster.Voornaam }} {{ verpleegster.Achternaam }}</h2>
                      <p>{{ verpleegster.Specialiteit }}</p>
                  </ion-label>
                  <ion-button color="danger"
                      @click="deleteVerpleegster(verpleegster.VerpleegsterID)">Verwijder</ion-button>
              </ion-item>
          </ion-list>
          <!-- Verpleegster list -->
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter  } from 'vue-router';
import axios from 'axios';
import {logOutOutline } from 'ionicons/icons';
import {
  IonPage,
  IonHeader,
  IonIcon,
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
  IonButton,
  IonList,
  IonSelect,
  IonSelectOption,
  IonDatetime
} from '@ionic/vue';


const router = useRouter();

const verpleegsters = ref([]);
const verpleegstervoornaam = ref('');
const verpleegsterachternaam = ref('');
const specialiteit = ref('');
const telefoon = ref('');
const email = ref('');
const username = ref('');
const wachtwoord = ref('');



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

const fetchVerpleegsters = () => {
  axios.get('https://gauravghimire.be/API_modernCare/api/VerpleegsterGet.php')
      .then(response => {
          verpleegsters.value = response.data.data;
      })
      .catch(error => {
          console.error('Error fetching verpleegsters:', error);
      });
};

const addVerpleegster = () => {
  axios.post('https://gauravghimire.be/API_modernCare/api/VerpleegsterAdd.php', {
      Voornaam: verpleegstervoornaam.value,
      Achternaam: verpleegsterachternaam.value,
      Telefoonnummer: telefoon.value,
      Email: email.value,
      Specialiteit: specialiteit.value,
      username: username.value,
      wachtwoord: wachtwoord.value
  })
      .then(response => {
          if (response.data.status === 'ok') {
              console.log('Verpleegster and User added successfully:', response.data);
              fetchVerpleegsters();
              clearFormVerpleegster();
          } else {
              console.error('Error adding Verpleegster and User:', response.data);
          }
      })
      .catch(error => {
          console.error('Error adding Verpleegster and User:', error);
      });
};

const deleteVerpleegster = (verpleegsterID) => {
  axios.post('https://gauravghimire.be/API_modernCare/api/VerpleegsterDelete.php', { VerpleegsterID: verpleegsterID })
      .then(response => {
          if (response.data.status === 'ok') {
              console.log('Verpleegster deleted successfully:', response.data);
              fetchVerpleegsters();
          } else {
              console.error('Error deleting verpleegster:', response.data);
          }
      })
      .catch(error => {
          console.error('Error deleting verpleegster:', error);
      });
};

const clearFormVerpleegster = () => {
  verpleegstervoornaam.value = '';
  verpleegsterachternaam.value = '';
  telefoon.value = '';
  email.value = '';
  specialiteit.value = '';
  username.value = '';
  wachtwoord.value = ''
};

onMounted(() => {
  const userData = JSON.parse(localStorage.getItem('userData'));
  if (!userData || userData.IsAdmin !== 1) {
  router.push('/tabs/tabHome');
  }
  fetchVerpleegsters();
});



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
const userData = JSON.parse(localStorage.getItem('userData'));
if (!userData || userData.IsAdmin !== 1) {
  router.push('/tabs/tabHome');
}
fetchPatients();
fetchKamers();
});

watch(blokNaam, () => {
updateVerdiepingen();
});

watch(verdieping, () => {
updateKamerNummers();
});

const logout = () => {
localStorage.removeItem('userData'); 
router.push('/tabs/tabLogin');
};
</script>

<style scoped>
.container {
display: flex;
flex-direction: row;
justify-content: space-between;
}

.management-section {
flex: 1;
margin: 0 10px;
}
</style>