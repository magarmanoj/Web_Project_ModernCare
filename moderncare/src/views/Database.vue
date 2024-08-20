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
              <ion-grid class="patient-grid">
                <ion-row class="table-header">
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
                    <span class="mobile-label">Voornaam:</span>
                    <span v-if="!patient.isEditing">{{ patient.Voornaam }}</span>
                    <ion-input v-else v-model="patient.Voornaam" placeholder="Voornaam" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Achternaam:</span>
                    <span v-if="!patient.isEditing">{{ patient.Achternaam }}</span>
                    <ion-input v-else v-model="patient.Achternaam" placeholder="Achternaam" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Leeftijd:</span>
                    <span v-if="!patient.isEditing">{{ patient.Leeftijd }}</span>
                    <ion-input v-else v-model="patient.Leeftijd" placeholder="Leeftijd" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Geslacht:</span>
                    <span v-if="!patient.isEditing">{{ patient.Geslacht }}</span>
                    <ion-select v-else v-model="patient.Geslacht">
                      <ion-select-option value="Man">Man</ion-select-option>
                      <ion-select-option value="Vrouw">Vrouw</ion-select-option>
                    </ion-select>
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">OpnameDatum:</span>
                    <span v-if="!patient.isEditing">{{ patient.OpnameDatum }}</span>
                    <ion-input v-else v-model="patient.OpnameDatum" placeholder="OpnameDatum" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">OntslagDatum:</span>
                    <span v-if="!patient.isEditing">{{ patient.OntslagDatum }}</span>
                    <ion-input v-else v-model="patient.OntslagDatum" placeholder="OntslagDatum" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Bloknaam:</span>
                    <span v-if="!patient.isEditing">{{ patient.BlokNaam }}</span>
                    <ion-input v-else v-model="patient.BlokNaam" placeholder="Bloknaam" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Verdieping:</span>
                    <span v-if="!patient.isEditing">{{ patient.Verdieping }}</span>
                    <ion-input v-else v-model="patient.Verdieping" placeholder="Verdieping" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Kamer Nummer:</span>
                    <span v-if="!patient.isEditing">{{ patient.KamerNummer }}</span>
                    <ion-input v-else v-model="patient.KamerNummer" placeholder="Kamer Nummer" />
                  </ion-col>
                  <ion-col>
                    <div class="actions-row">
                      <ion-button v-if="patient.isEditing" color="success"
                        @click="savePatient(patient)">Opslaan</ion-button>
                      <ion-button v-if="patient.isEditing" color="medium"
                        @click="cancelEdit(patient)">Annuleren</ion-button>
                      <ion-button v-else color="primary" @click="editPatient(patient)">Bewerken</ion-button>
                      <ion-button color="danger" @click="deletePatient(patient.PatiëntID)">Verwijder</ion-button>
                    </div>
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
              <ion-grid class="verpleegster-grid">
                <ion-row class="table-header">
                  <ion-col><strong>Voornaam</strong></ion-col>
                  <ion-col><strong>Achternaam</strong></ion-col>
                  <ion-col><strong>Specialiteit</strong></ion-col>
                  <ion-col><strong>Email</strong></ion-col>
                  <ion-col><strong>Telefoon</strong></ion-col>
                  <ion-col><strong>Acties</strong></ion-col>
                </ion-row>
                <ion-row v-for="verpleegster in verpleegsters" :key="verpleegster.VerpleegsterID">
                  <ion-col>
                    <span class="mobile-label">Voornaam:</span>
                    <span v-if="!verpleegster.isEditing">{{ verpleegster.Voornaam }}</span>
                    <ion-input v-else v-model="verpleegster.Voornaam" placeholder="Voornaam" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Achternaam:</span>
                    <span v-if="!verpleegster.isEditing">{{ verpleegster.Achternaam }}</span>
                    <ion-input v-else v-model="verpleegster.Achternaam" placeholder="Achternaam" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Specialiteit:</span>
                    <span v-if="!verpleegster.isEditing">{{ verpleegster.Specialiteit }}</span>
                    <ion-input v-else v-model="verpleegster.Specialiteit" placeholder="Specialiteit" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Email:</span>
                    <span v-if="!verpleegster.isEditing">{{ verpleegster.Email }}</span>
                    <ion-input v-else v-model="verpleegster.Email" placeholder="Email" />
                  </ion-col>
                  <ion-col>
                    <span class="mobile-label">Telefoon:</span>
                    <span v-if="!verpleegster.isEditing">{{ verpleegster.Telefoonnummer }}</span>
                    <ion-input v-else v-model="verpleegster.Telefoonnummer" placeholder="Telefoonnummer" />
                  </ion-col>
                  <ion-col>
                    <div class="actions-row">
                      <ion-button v-if="verpleegster.isEditing" color="success"
                        @click="saveVerpleegster(verpleegster)">Opslaan</ion-button>
                      <ion-button v-if="verpleegster.isEditing" color="medium"
                        @click="cancelEditVerpleegster(verpleegster)">Annuleren</ion-button>
                      <ion-button v-else color="primary" @click="editVerpleegster(verpleegster)">Bewerken</ion-button>
                      <ion-button color="danger" @click="deleteVerpleegster(verpleegster)">Verwijder</ion-button>
                    </div>
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
import { ref, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonGrid, IonRow, IonCol, IonInput, IonButton, IonIcon, IonCardContent, IonCard, IonCardHeader, IonCardTitle, IonSelect, IonSelectOption } from '@ionic/vue';
import { logOutOutline } from 'ionicons/icons';

const router = useRouter();
const route = useRoute();

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
        console.log(response.data);
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
const deleteVerpleegster = (verpleegster) => {
  if (verpleegster.IsAdmin) {
    console.error('Cannot delete an admin verpleegster.');
    alert('Cannot delete an admin verpleegster.');
    return;
  }

  axios.post('https://gauravghimire.be/API_modernCare/api/VerpleegsterDelete.php', { VerpleegsterID: verpleegster.VerpleegsterID })
    .then(response => {
      if (response.data.status === 'ok') {
        fetchVerpleegsters();
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

watch(() => route.path, () => {
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

.table-header {
  display: none;
}

.info-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.actions-row {
  display: flex;
  justify-content: space-around;
  margin-top: 10px;
}

/* Styles voor grotere scherms */
@media (min-width: 769px) {
  .table-header {
    display: flex;
  }

  .patient-grid .info-row,
  .verpleegster-grid .info-row {
    display: flex;
    flex-direction: row;
  }

  .mobile-label {
    display: none;
  }
}

/* Styles voor kleinere scherms */
@media (max-width: 768px) {
  .table-header {
    display: none;
  }

  ion-grid {
    display: block;
  }

  ion-row {
    display: block;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  ion-col {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
  }

  .mobile-label {
    font-weight: bold;
    margin-bottom: 5px;
  }

  .actions-row {
    flex-direction: column;
  }
}
</style>
