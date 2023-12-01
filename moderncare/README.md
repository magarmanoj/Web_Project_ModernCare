Ik een extra page gemaakt voor Setting waar je zelf kan kiezen tussen darkmode of light mode. 
Dit heb ik gedaan door toggle value op localstorage to save en zo te mode te veranderen. 

Het media query dus user device preference werkt ook, maar er zijn paar bug (als het user device in darkmode is, kan je niet toggelen naar light mode 
maar je kan wel toggelen tussen dark en lightmode als het user device in LIGHTMODE staat).

Toogle value updaten met die value van user device preference kan ik bereiken door volgende: (maar paar bugs).
<ion-item>
  <ion-toggle :prefersDarkMode="darkmode" @ion-change="toggleDarkMode">Darkmode <ion-icon :icon="moon"></ion-icon></ion-toggle>
</ion-item>

const systemDarkModePreference = ref(window.matchMedia('(prefers-color-scheme: dark)').matches);
const prefersDarkMode = ref(systemDarkModePreference);
const darkmode = ref(false);

(toggle werkt met local dus kan niet updaten met user preference).

IK HEB BEIDE VERSION VIDEO GEZET.
MET local (DEMO>EXTRA) en met user preference (DEMO- lightmode en darkmode).
