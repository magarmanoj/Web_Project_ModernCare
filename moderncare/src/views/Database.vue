<template>
    <ion-page>
      <ion-header>
        <ion-toolbar>
          <ion-title>Database</ion-title>
          <ion-button slot="end" @click="logout">
            <ion-icon :icon="logOutOutline"></ion-icon>
          </ion-button>
        </ion-toolbar>
      </ion-header>
  
      <ion-content class="ion-padding">
        <div class="container">
          <!-- Patient List -->
          <div class="management-section">
            <ion-card>
              <ion-card-header>
                <ion-card-title>Patiënt Lijst</ion-card-title>
              </ion-card-header>
              <ion-card-content>
                <ion-list>
                  <ion-item v-for="patient in patients" :key="patient.PatiëntID">
                    <ion-label>
                      <h2>{{ patient.Voornaam }} {{ patient.Achternaam }}</h2>
                      <p>{{ patient.Geslacht }}, {{ patient.Leeftijd }} jaar</p>
                    </ion-label>
                    <ion-button color="danger" @click="deletePatient(patient.PatiëntID)">Verwijder</ion-button>
                  </ion-item>
                </ion-list>
              </ion-card-content>
            </ion-card>
          </div>
  
          <!-- Verpleegster List -->
          <div class="management-section">
            <ion-card>
              <ion-card-header>
                <ion-card-title>Verpleegster Lijst</ion-card-title>
              </ion-card-header>
              <ion-card-content>
                <ion-list>
                  <ion-item v-for="verpleegster in verpleegsters" :key="verpleegster.VerpleegsterID">
                    <ion-label>
                      <h2>{{ verpleegster.Voornaam }} {{ verpleegster.Achternaam }}</h2>
                      <p>{{ verpleegster.Specialiteit }}</p>
                    </ion-label>
                    <ion-button color="danger" @click="deleteVerpleegster(verpleegster.VerpleegsterID)">Verwijder</ion-button>
                  </ion-item>
                </ion-list>
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
  import { logOutOutline } from 'ionicons/icons';
  
  const router = useRouter();
  
  // Verpleegster management
  const verpleegsters = ref([]);
  const fetchVerpleegsters = () => {
    axios.get('https://gauravghimire.be/API_modernCare/api/VerpleegsterGet.php')
      .then(response => {
        verpleegsters.value = response.data.data;
      })
      .catch(error => {
        console.error('Error fetching verpleegsters:', error);
      });
  };
  
  const deleteVerpleegster = (verpleegsterID) => {
    axios.post('https://gauravghimire.be/API_modernCare/api/VerpleegsterDelete.php', { VerpleegsterID: verpleegsterID })
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
  
  // Patient management
  const patients = ref([]);
  const fetchPatients = () => {
    axios.get('https://gauravghimire.be/API_modernCare/api/PatientGet.php')
      .then(response => {
        patients.value = response.data.data;
      })
      .catch(error => {
        console.error('Error fetching patients:', error);
      });
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
  
  // Logout functionality
  const logout = () => {
    localStorage.removeItem('userData');
    router.push('/tabs/tabLogin');
  };
  
  onMounted(() => {
    const userData = JSON.parse(localStorage.getItem('userData'));
    if (!userData || userData.IsAdmin !== 1) {
      router.push('/tabs/tabHome');
    }
    fetchVerpleegsters();
    fetchPatients();
  });
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
  