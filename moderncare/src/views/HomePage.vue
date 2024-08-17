<template>
  <ion-page>
    <ion-tabs>
      <ion-router-outlet></ion-router-outlet>
      <ion-tab-bar slot="bottom">
        <ion-tab-button tab="home" href="/tabs/tabHome">
          <ion-icon aria-hidden="true" :icon="home" />
          <ion-label>Patient</ion-label>
        </ion-tab-button>

        <!-- LogIn tab, only visible when the user is not logged in -->
        <ion-tab-button v-if="!isLoggedIn" tab="login" href="/tabs/tabLogin">
          <ion-icon aria-hidden="true" :icon="desktop" />
          <ion-label>LogIn</ion-label>
        </ion-tab-button>

        <!-- Lijsten tab, only visible when the user is logged in -->
        <ion-tab-button v-if="isLoggedIn" tab="lijsten" href="/tabs/tabLijsten">
          <ion-icon aria-hidden="true" :icon="list" />
          <ion-label>Lijsten</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="admin" href="/tabs/tabAdmin">
          <ion-icon aria-hidden="true" :icon="person" />
          <ion-label>Admin</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="about" href="/tabs/tabAbout">
          <ion-icon aria-hidden="true" :icon="informationCircle" />
          <ion-label>About</ion-label>
        </ion-tab-button>
      </ion-tab-bar>
    </ion-tabs>
  </ion-page>
</template>

<script setup>
import { IonTabBar, IonTabButton, IonTabs, IonLabel, IonIcon, IonPage, IonRouterOutlet } from '@ionic/vue';
import { desktop, informationCircle, home, person, list } from 'ionicons/icons';
import { ref, onMounted } from 'vue';

// Reactive variable to track login status
const isLoggedIn = ref(false);

onMounted(() => {
  const userData = JSON.parse(localStorage.getItem('userData'));
  isLoggedIn.value = !!userData; // Set to true if userData exists, otherwise false
});

</script>
