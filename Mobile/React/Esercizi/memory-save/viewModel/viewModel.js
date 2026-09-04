import CommunicationController from "../model/CommunicationController"
import StorageMenager from "../model/StorageMenager";

export default class viewModel{
    static async fetchData() {
        try {
            const lat = 45.4642;
            const lng = 9.19;
            const storage = new StorageMenager();
            let menu = {}; // Oggetto menu da popolare
    
            // Recupero della lista dei menu
            const menuList = await CommunicationController.getMenu(lat, lng);
    
            if (menuList.length > 0) {
                const firstMenu = menuList[0];
                console.log(firstMenu)
                const test = await storage.getMenu(firstMenu.mid);
                console.log(test)

                if(!test){
                    console.log("Menu non trovato")
                    const menuImg = await CommunicationController.getMenuImg(firstMenu.mid);
                    await storage.insertMenu(firstMenu.mid, firstMenu.imageVersion, menuImg.base64);
                    menu = {
                        mid: firstMenu.mid,
                        name: firstMenu.name,
                        price: firstMenu.price,
                        location: firstMenu.location,
                        imgVersion: firstMenu.imageVersion,
                        deliveryTime: firstMenu.deliveryTime,
                        shortDescription: firstMenu.shortDescription,
                        base64: menuImg.base64, 
                    };
                }else{
                    if(test.ImgVersion==firstMenu.imageVersion){
                        console.log("Menu Trovato nel database")
                        menu = {
                            mid: firstMenu.mid,
                            name: firstMenu.name,
                            price: firstMenu.price,
                            location: firstMenu.location,
                            imgVersion: test.ImgVersion,
                            deliveryTime: firstMenu.deliveryTime,
                            shortDescription: firstMenu.shortDescription,
                            base64: test.Base64, 
                        };
                    }else{
                        console.log("Versione immagine datata")
                        const menuImg = await CommunicationController.getMenuImg(firstMenu.mid);
                        await storage.insertMenu(menu.mid, menu.imgVersion, menu.base64);
                        menu = {
                            mid: firstMenu.mid,
                            name: firstMenu.name,
                            price: firstMenu.price,
                            location: firstMenu.location,
                            imgVersion: firstMenu.imgVersion,
                            deliveryTime: firstMenu.deliveryTime,
                            shortDescription: firstMenu.shortDescription,
                            base64: menuImg.base64, 
                        };
                    }
                }
            } else {
                console.warn('Nessun menu trovato nella posizione specificata.');
            }
    
            return menu; // Ritorna l'oggetto menu
        } catch (error) {
            console.error('Errore durante il caricamento dei dati del menu:', error);
            throw error; // Propaga l'errore per la gestione a livello superiore
        }
    }
    
}