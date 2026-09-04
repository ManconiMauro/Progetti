import * as SQLite from 'expo-sqlite';

export default class StorageManager {
    constructor() {
        this.db = null; // Il database sarà inizializzato in openDB
    }

    // Metodo per aprire il database e creare la tabella se non esiste
    async openDB() {
        if (!this.db) {
            this.db = await SQLite.openDatabaseAsync('userDB'); // Apri il database
        }
    
        // Assicurati che la tabella sia definita correttamente
        const query = `
            CREATE TABLE IF NOT EXISTS Dishes (
                ID INTEGER PRIMARY KEY AUTOINCREMENT, 
                Mid INTEGER UNIQUE,                  
                ImgVersion INTEGER NOT NULL,
                Base64 TEXT NOT NULL
            );
        `;
        try {
            await this.db.execAsync(query);
            console.log('Tabella Dishes creata/verificata con successo');
        } catch (error) {
            console.error('Errore nella creazione della tabella:', error);
        }
    }
    

    // Metodo per inserire un menu nella tabella
    async insertMenu(mid, imgVersion, base64) {
        try {
            // Assicurati che il database sia aperto
            if (!this.db) {
                await this.openDB();
            }
    
            // Query per inserire manualmente il Mid e gli altri valori
            const query = `
                INSERT INTO Dishes (Mid, ImgVersion, Base64)
                VALUES (?, ?, ?)
            `;
            await this.db.runAsync(query, [mid, imgVersion, base64]);
            console.log('Menu salvato con successo:', { mid, imgVersion});
        } catch (error) {
            // Gestione dell'errore
            if (error.message.includes('UNIQUE constraint failed')) {
                console.error('Errore: Mid già presente nel database.');
            } else {
                console.error('Errore durante il salvataggio del menu:', error);
            }
        }
    }
    

    // Metodo per recuperare un menu dato il Mid
    async getMenu(mid) {
        try {
            // Assicurati che il database sia aperto
            if (!this.db) {
                await this.openDB();
            }

            // Query di selezione
            const query = 'SELECT * FROM Dishes WHERE Mid = ?';
            const result = await this.db.getFirstAsync(query, [mid]);

            if (result) {
                console.log('Menu trovato!!!!!!!!!');
                return result;
            } else {
                console.warn('Nessun menu trovato con Mid:', mid);
                return null;
            }
        } catch (error) {
            console.error('Errore durante il recupero del menu:', error);
            return null; // Ritorna null in caso di errore
        }
    }

    async getAllMenus(){
        try {
            if (!this.db) {
                await this.openDB();
            }

            // Query di selezione
            const query = 'SELECT * FROM Dishes';
            const result = await this.db.getFirstAsync(query);

            if (result) {
                console.log('Menu trovato:', result);
            } else {
                console.warn('Nessun menu trovato con Mid:', mid);
                return null;
            }
        } catch (error) {
            console.error('Errore durante il recupero del menu:', error);
            return null; // Ritorna null in caso di errore
        }
    }
}
