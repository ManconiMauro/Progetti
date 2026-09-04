import CommunicationController from "../model/CommunicationController";
import StorageMenager from "../model/StorageMenager";
import * as Location from 'expo-location';

export default class viewModel {
    static async fetchData(userLocation) {
        try {
          const lat = userLocation.latitude;
          const lng = userLocation.longitude;
          console.log(lat, lng)
          let allMenus = []; 
      
          // Recupero della lista dei menu
          const menuList = await CommunicationController.getMenus(lat, lng);
          console.log("Lista menu ricevuta da CommunicationController:", menuList);
      
          if (menuList.length > 0) {
            for (let i = 0; i < menuList.length; i++) {
              let menu = await viewModel.retrieveMenu(menuList[i])
              allMenus.push(menu)
            }
          } else {
            console.warn("Nessun menu trovato nella posizione specificata.");
          }
      
          return allMenus;
        } catch (error) {
          console.error("Errore durante il caricamento dei dati del menu:", error);
          throw error; // Propaga l'errore per la gestione a livello superiore
        }
    }

    static async retrieveMenu(singleMenu){
        const storage = new StorageMenager();
        await storage.openDB()

        const test = await storage.getMenu(singleMenu.mid);
        console.log(singleMenu)
      
        if(!test){
          console.log("Menu non trovato in storage, caricamento immagine...");
          const menuImg = await CommunicationController.getMenuImg(singleMenu.mid);
          await storage.insertMenu(singleMenu.mid, singleMenu.imageVersion, menuImg.base64);
      
          const menu = {
            mid: singleMenu.mid,
            name: singleMenu.name,
            price: singleMenu.price,
            location: singleMenu.location,
            imgVersion: singleMenu.imageVersion,
            deliveryTime: singleMenu.deliveryTime,
            shortDescription: singleMenu.shortDescription,
            base64: menuImg.base64, // Evita di usarlo in console.log per lunghezza
          };
          return menu;
        }else if (test.ImgVersion !== singleMenu.imageVersion) {
          console.log("Manu trovato con versione immagine modificata");
          const menuImg = await CommunicationController.getMenuImg(singleMenu.mid);
          await storage.updateMenu(singleMenu.mid, singleMenu.imageVersion, menuImg.base64);
      
          const menu = {
            mid: singleMenu.mid,
            name: singleMenu.name,
            price: singleMenu.price,
            location: singleMenu.location,
            imgVersion: singleMenu.imageVersion,
            deliveryTime: singleMenu.deliveryTime,
            shortDescription: singleMenu.shortDescription,
            base64: menuImg.base64, // Evita di usarlo in console.log per lunghezza
          };
          return menu;
        } else {
          console.log("Menu trovato in storage con versioni compatibili.");
          const menu = {
            mid: singleMenu.mid,
            name: singleMenu.name,
            price: singleMenu.price,
            location: singleMenu.location,
            imgVersion: singleMenu.imageVersion,
            deliveryTime: singleMenu.deliveryTime,
            base64: test.Base64,
          };
          return menu;
        }
    }
     
    static async fetchUser(){
        let user;
        try {
            console.log("Attempting API call");
            user = await CommunicationController.getUser();
        } catch (error) {
            console.error("Error during call:", error);
            return null;
        }
        console.log(user);
        return user;
    }

    static async locationPermission(){
        try {
            let canUseLocation = this.checkPermission()
            if (!canUseLocation) {
                throw new Error('I permessi di localizzazione non sono stati concessi.');
            }
            
            // Ottieni la posizione dell'utente
            const userLocation = await Location.getCurrentPositionAsync();
            return userLocation.coords;
        } catch (error) {
            console.error("Errore durante l'acquisizione della posizione o dell'ordine:", error);
        }
    }

    static async makeOrder(mid){
        try {
            let canUseLocation = await this.checkPermission()
            if (!canUseLocation) {
                throw new Error('I permessi di localizzazione non sono stati concessi.');
              }
            
            // Ottieni la posizione dell'utente
            const userLocation = await Location.getCurrentPositionAsync();
            const coords = userLocation.coords;
        
            // Ottieni i dati dell'utente
            const user = await CommunicationController.getUser();
        
            let order = null;
        
            if (user.orderStatus === 'ON_DELIVERY') {
              // Se l'utente ha un ordine in corso
              order = await CommunicationController.getOrder(user.lastOid);
              console.log("Ordine attivo trovato:", order);
            } else {
              // Se non c'è un ordine, crea un nuovo ordine
              order = await CommunicationController.buyMenu(mid, coords);
              console.log("Nuovo ordine creato:", order);
            }
        
            return {
              userLocation: coords,
              order,
            };
          } catch (error) {
            console.error("Errore durante l'acquisizione della posizione o dell'ordine:", error);
            throw error; // Propaga l'errore per gestirlo nel componente
          }
    }

    static async checkPermission(){
        const grantedPermission = await Location.getForegroundPermissionsAsync();
        
        if (grantedPermission.status === 'granted') {
          return true
        } else {
          const permissionResponse = await Location.requestForegroundPermissionsAsync();
          if (permissionResponse.status === 'granted') {
            return true
          }
        }
        
        return false
    }

    static async fetchDishDetails(mid, lat, lng) {
        try {
          const details = await CommunicationController.getMenuDetails(mid, lat, lng);
          return details; // Assicurati che contenga shortDescription e longDescription
        } catch (error) {
          console.error('Errore durante il caricamento dei dettagli del piatto:', error);
          throw error;
        }
      }
      
}