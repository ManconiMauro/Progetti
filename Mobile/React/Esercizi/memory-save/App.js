import { StyleSheet, Text, View, Image } from 'react-native';
import React, { useEffect, useState } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import CommunicationController from './model/CommunicationController';
import viewModel from './viewModel/viewModel';

const App = () => {
  // Stato per salvare un singolo elemento del menu
  const [menuItem, setMenuItem] = useState(null);

  // Funzione per controllare le credenziali e caricare i dati
  const checkCredentials = async () => {
    try {
      const sid = await AsyncStorage.getItem('sid');
      const uid = await AsyncStorage.getItem('uid');

      if (sid == null || uid == null) {
        // Crea un nuovo utente se le credenziali non esistono
        const result = await CommunicationController.createUser();
        console.log('Nuovo utente creato:', result);

        await AsyncStorage.setItem('sid', result.sid); 
        await AsyncStorage.setItem('uid', String(result.uid));
      } else {
        console.log('Credenziali trovate, caricamento dei dati...');
        await fetchData(); // Chiamata a fetchData se le credenziali esistono
      }
    } catch (error) {
      console.error('Errore durante il controllo delle credenziali:', error);
    }
  };

  // Funzione per scaricare i dati utilizzando la tua fetchData
  const fetchData = async () => {
    try {
      const data = await viewModel.fetchData();    
      setMenuItem(data);
    } catch (error) {
      console.error('Errore nel caricamento dei dati:', error);
    }
  };

  // useEffect per controllare le credenziali al primo caricamento
  useEffect(() => {
    checkCredentials();
  }, []);

  // Controllo: se i dati non sono ancora caricati, mostra un testo di caricamento
  if (!menuItem) {
    return (
      <View style={styles.container}>
        <Text style={styles.loadingText}>Caricamento in corso...</Text>
      </View>
    );
  }

  // Render del contenuto una volta che i dati sono caricati
  return (
    <View style={styles.container}>
      {/* Nome del menu */}
      <Text style={styles.title}>{menuItem.name}</Text>

      {/* Immagine del menu */}
      <Image
        source={{ uri: `data:image/png;base64,${menuItem.base64}` }}
        style={styles.image}
      />

      {/* Descrizione breve */}
      <Text style={styles.description}>{menuItem.shortDescription}</Text>
    </View>
  );
};

// Stile per la tua app
const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 16,
    textAlign: 'center',
  },
  image: {
    width: 200,
    height: 200,
    marginBottom: 16,
    borderRadius: 8,
  },
  description: {
    fontSize: 16,
    color: '#555',
    textAlign: 'center',
  },
  loadingText: {
    textAlign: 'center',
    fontSize: 18,
    color: '#555',
  },
});

export default App;
