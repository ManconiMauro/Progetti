import CommunicationController from "../model/CommunicationController";
import StorageMenager from "../model/StorageMenager";

export default class viewModel {
    static async fetchData() {
        try {
          const lat = 45.4642;
          const lng = 9.19;
          const storage = new StorageMenager();
          let allMenus = []; 
      
          // Recupero della lista dei menu
          const menuList = await CommunicationController.getMenus(lat, lng);
          console.log("Lista menu ricevuta da CommunicationController:", menuList);
      
          if (menuList.length > 0) {
            for (let i = 0; i < menuList.length; i++) {
              const singleMenu = await CommunicationController.getMenuDetails(menuList[i].mid, lat, lng);
              const test = await storage.getMenu(singleMenu.mid);
      
              if (!test) {
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
                  longDescription: singleMenu.longDescription,
                  base64: menuImg.base64, // Evita di usarlo in console.log per lunghezza
                };
                allMenus.push(menu); // Corretto uso di push per aggiungere all'array
              } else {
                console.log("Menu trovato in storage con versioni compatibili.");
                const menu = {
                  mid: singleMenu.mid,
                  name: singleMenu.name,
                  price: singleMenu.price,
                  location: singleMenu.location,
                  imgVersion: singleMenu.imageVersion,
                  deliveryTime: singleMenu.deliveryTime,
                  shortDescription: singleMenu.shortDescription,
                  longDescription: singleMenu.longDescription,
                  base64: test.Base64,
                };
                allMenus.push(menu);
              }
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
      
}
