<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-title>Verpleegsters</ion-title>
                <ion-button slot="end" @click="logout">
                    <ion-icon slot="icon-only" :icon="logOutOutline"></ion-icon>
                </ion-button>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <ion-card>
                <ion-card-header>
                    <ion-card-title>Voeg Verpleegster Toe</ion-card-title>
                </ion-card-header>
                <ion-card-content>
                    <ion-item>
                        <ion-input label="Voornaam" v-model="voornaam"></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-input label="Achternaam" v-model="achternaam"></ion-input>
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
                        <ion-select label="Is Admin" v-model="isAdmin">
                            <ion-select-option value="1">Ja</ion-select-option>
                            <ion-select-option value="0">Nee</ion-select-option>
                        </ion-select>
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
        </ion-content>
    </ion-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
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
    IonSelectOption
} from '@ionic/vue';


const router = useRouter();

const verpleegsters = ref([]);
const voornaam = ref('');
const achternaam = ref('');
const specialiteit = ref('');
const telefoon = ref('');
const email = ref('');
const isAdmin = ref(0);
const username = ref('');
const wachtwoord = ref('');

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
        Voornaam: voornaam.value,
        Achternaam: achternaam.value,
        Telefoonnummer: telefoon.value,
        Email: email.value,
        Specialiteit: specialiteit.value,
        IsAdmin: isAdmin.value,
        username: username.value,
        wachtwoord: wachtwoord.value
    })
        .then(response => {
            if (response.data.status === 'ok') {
                console.log('Verpleegster and User added successfully:', response.data);
                fetchVerpleegsters();
                clearForm();
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

const clearForm = () => {
    voornaam.value = '';
    achternaam.value = '';
    telefoon.value = '';
    email.value = '';
    specialiteit.value = '';
    isAdmin.value = 0;
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

const logout = () => {
  localStorage.removeItem('userData'); 
  router.push('/tabs/tabLogin');
};
</script>
