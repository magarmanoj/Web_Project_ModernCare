<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-title class="ion-text-center">Home</ion-title>
                    <ion-button slot="end" @click="logout">
                        <ion-icon slot="icon-only" :icon="logOutOutline"></ion-icon>
                    </ion-button>
            </ion-toolbar>
        </ion-header>

        <ion-content class="homeContent">
            <ion-list>
                <ion-item v-for="(patient, index) in patients" :key="index">
                    <ion-label>
                        <h2>{{ patient.PatientVoornaam }} {{ patient.PatientAchternaam }}</h2>
                    </ion-label>
                    <ion-button @click="showKamerDetails(patient)">
                        <ion-icon slot="start" :icon="informationCircleOutline"></ion-icon>
                        Kamer Info
                    </ion-button>
                    <ion-button @click="showPrioriteitButtons(patient)">
                        <ion-icon slot="start" :icon="alertCircleSharp"></ion-icon>
                        Prioriteit
                    </ion-button>
                </ion-item>
            </ion-list>

            <ion-modal v-if="selectedPatient" :is-open="isModalOpen " :backdropDismiss="false">
                <ion-content>
                    <ion-card>
                        <ion-card-header>
                            <ion-card-title>Kamer Details</ion-card-title>
                        </ion-card-header>
                        <ion-card-content>
                            <p>Blok Naam: {{ selectedPatient.BlokNaam }}</p>
                            <p>Kamer Nummer: {{ selectedPatient.KamerNummer }}</p>
                            <p>Verdieping: {{ selectedPatient.Verdieping }}</p>
                        </ion-card-content>
                        <ion-button @click="closeModal">Close</ion-button>
                    </ion-card>
                </ion-content>
            </ion-modal>

            <ion-modal :is-open="isPrioriteitModalOpen" :backdropDismiss="false" >
                <ion-content>
                    <ion-card>
                        <ion-card-header>
                            <ion-card-title>Selecteer Prioriteit</ion-card-title>
                        </ion-card-header>
                        <ion-card-content>
                            <ion-grid class="homeGrid">
                                <ion-row>
                                    <ion-col class="ion-text-center">
                                        <ion-button @click="updateOngevalType('Hoog')" class="oproepBtn" expand="block">
                                            <font-awesome-icon :icon="['fas', 'circle-exclamation']" />
                                            Hoog
                                        </ion-button>
                                    </ion-col>
                                </ion-row>
                                <ion-row>
                                    <ion-col class="ion-text-center">
                                        <ion-button @click="updateOngevalType('Middel')" class="oproepBtn" expand="block">
                                            <font-awesome-icon :icon="['fas', 'person-falling-burst']" />
                                              Middel
                                        </ion-button>
                                    </ion-col>
                                </ion-row>
                                <ion-row>
                                    <ion-col class="ion-text-center">
                                        <ion-button @click="updateOngevalType('Laag')" class="oproepBtn" expand="block">
                                            <font-awesome-icon :icon="['fas', 'restroom']" />
                                            Laag
                                        </ion-button>
                                    </ion-col>
                                </ion-row>
                            </ion-grid>
                        </ion-card-content>
                        <ion-button @click="closePrioriteitModal">Close</ion-button>
                    </ion-card>
                </ion-content>
            </ion-modal>
        </ion-content>
    </ion-page>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { useRoute, useRouter  } from 'vue-router';

import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonGrid, IonRow, IonCol, IonButton, IonModal, IonList, IonCard, IonCardHeader, IonLabel, IonItem, IonCardContent, IonIcon, IonCardTitle } from '@ionic/vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { informationCircleOutline, alertCircleSharp, logOutOutline } from 'ionicons/icons';

library.add(fas);

const route = useRoute();
const router = useRouter();

const patients = ref([]);
const selectedPatient = ref(null);
const isModalOpen = ref(false);
const isPrioriteitModalOpen = ref(false);

const fetchDetails = () => {
    axios.post('https://gauravghimire.be/API_modernCare/api/GetPatientDetails.php')
        .then(response => {
            if (response.status === 200 && response.data.data) {
                const data = response.data.data;
                patients.value = data;
            } else {
                console.error('Data fetching error:', response.status);
            }
        })
        .catch(error => {
            console.error('API call error:', error);
        });
};

const showKamerDetails = (patient) => {
    selectedPatient.value = patient;
    isModalOpen.value = true;
};

const closeModal = () => {
    selectedPatient.value = null;
    isModalOpen.value = false;
};

const showPrioriteitButtons = (patient) => {
    selectedPatient.value = patient;
    isPrioriteitModalOpen.value = true;
};

const closePrioriteitModal = () => {
    isPrioriteitModalOpen.value = false;
};

const updateOngevalType = (prioriteit) => {
    axios.post('https://gauravghimire.be/API_modernCare/api/OngevalType.php', {
        PatiëntID: selectedPatient.value.PatiëntID,
        Prioriteit: prioriteit
    })
    .then(response => response.data)
    .then(responseData => {
        if (responseData.status === 'ok') {
            console.log(responseData.data);
            closePrioriteitModal();
        }
    })
    .catch(error => {
        console.error('Error saving project:', error);
        window.alert('Er is een fout opgetreden bij het opslaan van het project.');
    });
};

onMounted(() => {
    fetchDetails();
});

watch(() => route.path, () => {
    fetchDetails();
});

const logout = () => {
  localStorage.removeItem('userData'); 
  router.push('/tabs/tabLogin');
};
</script>

<style>
.homeContent {
    --ion-background-color: #f8f9fa;
}
.homeGrid {
    padding: 20px;
}
.oproepBtn {
    margin: 10px;
    width: 100%;
}

</style>
