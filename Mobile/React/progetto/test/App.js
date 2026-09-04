import React, { useState, useEffect } from 'react';
import { View, Text, TouchableOpacity } from 'react-native';
import HomeScreen from './HomeScreen';
import CommunicationController from './model/CommunicationController';
import commonStyles from './style/StyleSheet'; 
import AsyncStorage from '@react-native-async-storage/async-storage';

const App = () => {
  // Stato per tenere traccia della schermata corrente
  const [currentScreen, setCurrentScreen] = useState(null);

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
        await renderData(); 
      }
    } catch (error) {
      console.error('Errore durante il controllo delle credenziali:', error);
    }
  };

  const renderData= async ()=>{
    try {
      
      const screen = await AsyncStorage.getItem('primaryScreen')

      if(!screen){
        await AsyncStorage.setItem('primaryScreen', "Home")
        setCurrentScreen("Home")
      }else {
        setCurrentScreen(screen)
      }
    } catch (error) {
      console.error('Errore durante il controllo delle credenziali:', error);
    }
  }

  // useEffect per controllare le credenziali al primo caricamento
  useEffect(() => {
    checkCredentials();
  }, []);

  // Funzione per la navigazione
  const navigateTo = async (screen) => {
    await AsyncStorage.setItem('primaryScreen', screen)
    setCurrentScreen(screen);
  };

  if (!currentScreen) {
    return (
      <View style={commonStyles.container}>
        <Text style={commonStyles.loadingText}>Caricamento in corso...</Text>
      </View>
    );
  }
  return (
    <View style={commonStyles.appContainer}>
      <View style={commonStyles.screenContainer}>
      {currentScreen === 'Home' ? (
        <HomeScreen/>
      ) : (
        <Text>Come ci sei entrato qui?</Text>
      )}
      </View>
    </View>
  );
};

export default App;