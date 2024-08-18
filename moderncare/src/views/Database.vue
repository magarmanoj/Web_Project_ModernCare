<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Database Overzicht</ion-title>
        <ion-button slot="end" @click="logout">
          <ion-icon :icon="logOutOutline"></ion-icon>
        </ion-button>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      <div class="container">
        <!-- Patients Table -->
        <div class="table-section">
          <ion-card>
            <ion-card-header>
              <ion-card-title>Patiënten</ion-card-title>
            </ion-card-header>
            <ion-card-content>
              <ion-grid>
                <ion-row>
                  <ion-col><strong>Voornaam</strong></ion-col>
                  <ion-col><strong>Achternaam</strong></ion-col>
                  <ion-col><strong>Leeftijd</strong></ion-col>
                  <ion-col><strong>Geslacht</strong></ion-col>
                  <ion-col><strong>OpnameDatum</strong></ion-col>
                  <ion-col><strong>OntslagDatum</strong></ion-col>
                  <ion-col><strong>Bloknaam</strong></ion-col>
                  <ion-col><strong>Verdieping</strong></ion-col>
                  <ion-col><strong>Kamer Nummer</strong></ion-col>
                  <ion-col><strong>Acties</strong></ion-col>
                </ion-row>
                <ion-row v-for="(patient, index) in patients" :key="patient.PatiëntID">
                  <ion-col>
                    <ion-input v-if="patient.isEditing" v-model="patient.Voornaam" placeholder="Voornaam" />
                    <span v-else>{{ patient.Voornaam }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="patient.isEditing" v-model="patient.Achternaam" placeholder="Achternaam" />
                    <span v-else>{{ patient.Achternaam }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="patient.isEditing" v-model="patient.Leeftijd" placeholder="Leeftijd" />
                    <span v-else>{{ patient.Leeftijd }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-select v-if="patient.isEditing" v-model="patient.Geslacht">
                      <ion-select-option value="Man">Man</ion-select-option>
                      <ion-select-option value="Vrouw">Vrouw</ion-select-option>
                    </ion-select>
                    <span v-else>{{ patient.Geslacht }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="patient.isEditing" v-model="patient.OpnameDatum" placeholder="OpnameDatum" />
                    <span v-else>{{ patient.OpnameDatum }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="patient.isEditing" v-model="patient.OntslagDatum" placeholder="OntslagDatum" />
                    <span v-else>{{ patient.OntslagDatum }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="patient.isEditing" v-model="patient.BlokNaam" placeholder="Bloknaam" />
                    <span v-else>{{ patient.BlokNaam }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="patient.isEditing" v-model="patient.Verdieping" placeholder="Verdieping" />
                    <span v-else>{{ patient.Verdieping }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="patient.isEditing" v-model="patient.KamerNummer" placeholder="Kamer Nummer" />
                    <span v-else>{{ patient.KamerNummer }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-button v-if="patient.isEditing" color="success"
                      @click="savePatient(patient)">Opslaan</ion-button>
                    <ion-button v-if="patient.isEditing" color="medium"
                      @click="cancelEdit(patient)">Annuleren</ion-button>
                    <ion-button v-else color="primary" @click="editPatient(patient)">Bewerken</ion-button>
                    <ion-button color="danger" @click="deletePatient(patient.PatiëntID)">Verwijder</ion-button>
                  </ion-col>
                </ion-row>
              </ion-grid>
            </ion-card-content>
          </ion-card>
        </div>

        <!-- Verpleegsters Table -->
        <div class="table-section">
          <ion-card>
            <ion-card-header>
              <ion-card-title>Verpleegsters</ion-card-title>
            </ion-card-header>
            <ion-card-content>
              <ion-grid>
                <ion-row>
                  <ion-col><strong>Voornaam</strong></ion-col>
                  <ion-col><strong>Achternaam</strong></ion-col>
                  <ion-col><strong>Specialiteit</strong></ion-col>
                  <ion-col><strong>Email</strong></ion-col>
                  <ion-col><strong>Telefoon</strong></ion-col>
                  <ion-col><strong>Acties</strong></ion-col>
                </ion-row>
                <ion-row v-for="verpleegster in verpleegsters" :key="verpleegster.VerpleegsterID">
                  <ion-col>
                    <ion-input v-if="verpleegster.isEditing" v-model="verpleegster.Voornaam" placeholder="Voornaam" />
                    <span v-else>{{ verpleegster.Voornaam }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="verpleegster.isEditing" v-model="verpleegster.Achternaam"
                      placeholder="Achternaam" />
                    <span v-else>{{ verpleegster.Achternaam }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="verpleegster.isEditing" v-model="verpleegster.Specialiteit"
                      placeholder="Specialiteit" />
                    <span v-else>{{ verpleegster.Specialiteit }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="verpleegster.isEditing" v-model="verpleegster.Email" placeholder="Email" />
                    <span v-else>{{ verpleegster.Email }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-input v-if="verpleegster.isEditing" v-model="verpleegster.Telefoonnummer"
                      placeholder="Telefoonnummer" />
                    <span v-else>{{ verpleegster.Telefoonnummer }}</span>
                  </ion-col>
                  <ion-col>
                    <ion-button v-if="verpleegster.isEditing" color="success"
                      @click="saveVerpleegster(verpleegster)">Opslaan</ion-button>
                    <ion-button v-if="verpleegster.isEditing" color="medium"
                      @click="cancelEditVerpleegster(verpleegster)">Annuleren</ion-button>
                    <ion-button v-else color="primary" @click="editVerpleegster(verpleegster)">Bewerken</ion-button>
                    <ion-button color="danger"
                      @click="deleteVerpleegster(verpleegster.VerpleegsterID)">Verwijder</ion-button>
                  </ion-col>
                </ion-row>


              </ion-grid>
            </ion-card-content>
          </ion-card>
        </div>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonGrid, IonRow, IonCol, IonInput, IonButton, IonIcon, IonCardContent, IonCard, IonCardHeader, IonCardTitle, IonSelect, IonSelectOption } from '@ionic/vue';
import { logOutOutline } from 'ionicons/icons';

const router = useRouter();

const patients = ref([]);
const verpleegsters = ref([]);

// Fetch patients data
const fetchPatients = () => {
  axios.post('https://gauravghimire.be/API_modernCare/api/GetPatientDetails.php')
    .then(response => {
      if (response.data && response.data.data) {
        patients.value = response.data.data.map(patient => ({
          ...patient,
          isEditing: false, // Add an isEditing flag to each patient
        }));
      } else {
        console.error('Error: Unexpected response format', response);
      }
    })
    .catch(error => {
      console.error('Error fetching patients:', error);
    });
};

// Fetch verpleegsters data
const fetchVerpleegsters = () => {
  axios.get('https://gauravghimire.be/API_modernCare/api/VerpleegsterGet.php')
    .then(response => {
      if (response.data && response.data.data) {
        verpleegsters.value = response.data.data;
      } else {
        console.error('Error: Unexpected response format', response);
      }
    })
    .catch(error => {
      console.error('Error fetching verpleegsters:', error);
    });
};

// Edit patient
const editPatient = (patient) => {
  patient.isEditing = true;
};



// Cancel editing
const cancelEdit = (patient) => {
  patient.isEditing = false;
  fetchPatients(); // Refresh data to revert changes
};

// Delete patient
const deletePatient = (patientID) => {
  axios.post('https://gauravghimire.be/API_modernCare/api/PatientDelete.php', { PatiëntID: patientID })
    .then(response => {
      if (response.data.status === 'ok') {
        fetchPatients(); // Refresh the list after deletion
      } else {
        console.error('Error deleting patient:', response.data);
      }
    })
    .catch(error => {
      console.error('Error deleting patient:', error);
    });
};

// Delete verpleegster
const deleteVerpleegster = (verpleegsterID) => {
  axios.post('https://gauravghimire.be/API_modernCare/api/VerpleegsterDelete.php', { VerpleegsterID: verpleegsterID })
    .then(response => {
      if (response.data.status === 'ok') {
        fetchVerpleegsters(); // Refresh the list after deletion
      } else {
        console.error('Error deleting verpleegster:', response.data);
      }
    })
    .catch(error => {
      console.error('Error deleting verpleegster:', error);
    });
};

// Save patient
const savePatient = (patient) => {
  axios.post('https://gauravghimire.be/API_modernCare/api/UpdatePatientDetails.php', {
    PatiëntID: patient.PatiëntID,
    Voornaam: patient.Voornaam,
    Achternaam: patient.Achternaam,
    Leeftijd: patient.Leeftijd,
    Geslacht: patient.Geslacht,
    OpnameDatum: patient.OpnameDatum,
    OntslagDatum: patient.OntslagDatum,
    BlokNaam: patient.BlokNaam,
    Verdieping: patient.Verdieping,
    KamerNummer: patient.KamerNummer
  })
    .then(response => {
      if (response.data && response.data.code === 1) {
        patient.isEditing = false;
        console.log('Patient edited successfully');
      } else {
        console.error('Error updating patient:', response);
      }
    })
    .catch(error => {
      console.error('Error updating patient:', error);
    });
};
// Edit verpleegster
const editVerpleegster = (verpleegster) => {
  verpleegster.isEditing = true;
};

// Save verpleegster
const saveVerpleegster = (verpleegster) => {
  axios.post('https://gauravghimire.be/API_modernCare/api/UpdateVerpleegsterDetails.php', {
    VerpleegsterID: verpleegster.VerpleegsterID,
    Voornaam: verpleegster.Voornaam,
    Achternaam: verpleegster.Achternaam,
    Specialiteit: verpleegster.Specialiteit,
    Email: verpleegster.Email,
    Telefoonnummer: verpleegster.Telefoonnummer
  })
    .then(response => {
      console.log('Server response:', response.data);  // Log the raw response
      if (response.data.code === 1) {  // Check for success code
        verpleegster.isEditing = false;  // Exit edit mode on success
        console.log('Verpleegster edited successfully');
      } else {
        console.error('Error updating verpleegster:', response.data);
      }
    })
    .catch(error => {
      console.error('Error updating verpleegster:', error);
    });
};



// Cancel editing verpleegster
const cancelEditVerpleegster = (verpleegster) => {
  verpleegster.isEditing = false;
  fetchVerpleegsters(); // Refresh data to revert changes
};





// Initial data fetch on mount
onMounted(() => {
  fetchPatients();
  fetchVerpleegsters();
});

// Logout function
const logout = () => {
  localStorage.removeItem('userData');
  router.push('/tabs/tabLogin');
  setTimeout(() => {
    window.location.reload();
  }, 100);
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.table-section {
  margin-bottom: 20px;
}
</style>
