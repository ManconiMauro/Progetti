import React, { useState, useEffect } from 'react';
import { View, Text, ActivityIndicator, Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import commonStyles from './style/StyleSheet';
import viewModel from './viewModel/viewModel';
import CommunicationController from './api/CommunicationController';
import Navigation from './screens/Navigation';

const App = () => {
  const [initialScreen, setInitialScreen] = useState(false);
  const [lastScreen, setLastScreen] = useState("Home");
  const [userLocation, setUserLocation] = useState(null);
  const [order, setOrder] = useState(null);

  const retrieveLastScreen = async () => {
    try {
      const lastScreen = await AsyncStorage.getItem('lastScreen');
      setLastScreen(lastScreen || 'Home');
    } catch (error) {
      console.log("Errore nel recupero della schermata precedente:");
      setLastScreen('Home'); 
    }
  };

  const checkCredentialsAndLoadScreen = async () => {
    try {
      const sid = await AsyncStorage.getItem('sid');
      const uid = await AsyncStorage.getItem('uid');

      if (!sid || !uid) {
        const result = await CommunicationController.createUser();
        console.log('Nuovo utente creato:', result);
        await AsyncStorage.setItem('sid', result.sid);
        await AsyncStorage.setItem('uid', String(result.uid));
      }
      console.log("Utente creato oppure già loggato");
      checkAndgetUserLocation();
    } catch (error) {
      console.error('Errore durante il controllo delle credenziali:', error);
    }
  };

  const checkAndgetUserLocation = async () => {
    let userLocation = await viewModel.locationPermission();
    setUserLocation(userLocation);
    console.log("Location in App: ", userLocation);
    setInitialScreen(true);
  };

  const handleNavigateToMap = async (navigation) => {
    try {
      if (userLocation) {
        const data = await viewModel.locationPermission();
        setUserLocation(data);
      }
      console.log(userLocation);
      navigation.navigate('Map');
    } catch (error) {
      console.error('Errore durante il caricamento dei dati per la mappa:', error);
    }
  };

  const buyMenu = async (navigation, piatto) => {
    try {
      const user = await CommunicationController.getUser();
      if (user.firstName == null || user.cardNumber == null) {
        Alert.alert(
          "Inserire dati del profilo", 
          "I Dati del Profilo non sono stati inseriti!",
          [{ text: "OK" }]
        );
        return;
      } else if (user.orderStatus === "ON_DELIVERY") {
        Alert.alert(
          "Ordine in Corso", 
          "Hai già un ordine in corso, non puoi fare un nuovo ordine finché non viene consegnato.",
          [{ text: "OK" }]
        );
        return;
      } else {
        const data = await viewModel.makeOrder(piatto.mid);
        console.log(data.userLocation, data.order);
        await AsyncStorage.setItem('restaurant', JSON.stringify(piatto.location));
        setUserLocation(data.userLocation);
        setOrder(data.order);
        navigation.navigate('Map');
      }
    } catch (error) {
      console.error('Errore durante il caricamento dei dati per la mappa:', error);
    }
  };

  useEffect(() => {
    const loadAppData = async () => {
      await Promise.all([retrieveLastScreen(), checkCredentialsAndLoadScreen()]);
    };
    loadAppData();
  }, []);

  if (!initialScreen) {
    return (
      <View style={commonStyles.container}>
        <ActivityIndicator size="large" color="#007bff" />
        <Text style={commonStyles.loadingText}>Caricamento in corso...</Text>
      </View>
    );
  }

  return (
    <Navigation 
      lastScreen={lastScreen} 
      userLocation={userLocation} 
      buyMenu={buyMenu} 
      order={order} 
      handleNavigateToMap={handleNavigateToMap} 
      onBackClick={onBackClick}
    />
  );
};

export default App;
