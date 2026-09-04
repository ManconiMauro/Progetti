import React, { useState, useEffect } from 'react';
import { View, Text, Image, Alert } from 'react-native';
import MapView, { Marker } from 'react-native-maps';
import CommunicationController from './model/CommunicationController';
import commonStyles from './style/StyleSheet';
import { useFocusEffect } from '@react-navigation/native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import viewModel from './viewModel/viewModel';
import { ActivityIndicator } from 'react-native';

const MapScreen = ({ userLocation, order }) => {
  const [currentOrder, setCurrentOrder] = useState(order);
  const [orderCompleted, setOrderCompleted] = useState(false);
  const [restaurant, setRestaurant] = useState(null);
  const [alertShown, setAlertShown] = useState(false);
  const [hasLocationPermission, setHasLocationPermission] = useState(false);

  // Funzione per recuperare i dati dell'utente e l'ordine
  const fetchUserData = async () => {
    try {
      // Recuperiamo i dati dell'utente, incluso l'ID dell'ordine e lo stato dell'ordine
      const userData = await CommunicationController.getUser(); // Supponiamo che getUser restituisca { lastOid, orderStatus }

      if (userData) {
        const { lastOid, orderStatus } = userData;

        // Se l'ordine è COMPLETED, resettiamo currentOrder e ristorante
        if (orderStatus === 'COMPLETED') {
          setCurrentOrder(null);
          setRestaurant(null);
          AsyncStorage.removeItem('restaurant').catch(error => console.log("Errore nella rimozione: ", error));
          setOrderCompleted(true);  // Impostiamo l'ordine come completato
        } else {
          // Se c'è un ordine in corso, recuperiamo i dettagli dell'ordine
          const updatedOrder = await CommunicationController.getOrder(lastOid);
          setCurrentOrder(updatedOrder);
        }
      }
    } catch (error) {
      console.error("Errore nel recupero dei dati dell'utente:", error);
    }
  };

  const fetchRestaurant = async () => {
    try {
      const storedRestaurant = await AsyncStorage.getItem('restaurant');
      if (storedRestaurant) {
        setRestaurant(JSON.parse(storedRestaurant));
      }
    } catch (error) {
      setRestaurant(null)
    }
  };
  
  const checkPermission = async () => {
    const permissionGranted = await viewModel.checkPermission();
    setHasLocationPermission(permissionGranted);

    if (!permissionGranted) {
      Alert.alert(
        "Permessi di posizione necessari",
        "Abilita la posizione per accedere alla mappa.",
        [{ text: "OK", onPress: () => AsyncStorage.setItem('lastScreen', 'Home').catch(error => console.log("Errore nel salvataggio: ", error))}]
      );
    }else{
      AsyncStorage.setItem('lastScreen', 'Map').catch(error => console.log("Errore nel salvataggio: ", error));
    }
  }

  useEffect(() => {
    checkPermission();
  }, [])

  useFocusEffect(
    React.useCallback(() => {
      fetchUserData();
      fetchRestaurant();

      let intervalId = null;

      if (currentOrder) {
        const startPolling = async () => {
          intervalId = setInterval(async () => {
            try {
              const updatedOrder = await CommunicationController.getOrder(currentOrder.oid);
              console.log("Stato dell'ordine aggiornato:", updatedOrder.status);

              setCurrentOrder((prevOrder) => ({
                ...prevOrder,
                currentPosition: updatedOrder.currentPosition,
                status: updatedOrder.status,
              }));

              // 🔔 Mostra l'alert solo una volta quando lo stato diventa COMPLETED
              if (updatedOrder.status === 'COMPLETED' && !alertShown) {
                Alert.alert("Il tuo ordine è arrivato!", "Il drone ha raggiunto la tua posizione.");
                setAlertShown(true); 
                clearInterval(intervalId);
                setOrderCompleted(true);
                setCurrentOrder(null);
                AsyncStorage.removeItem('restaurant').catch(error => console.log("Errore nella rimozione: ", error));
                setRestaurant(null);
              }
            } catch (error) {
              console.error("Errore durante l'aggiornamento della posizione:", error);
            }
          }, 2000);
        };

        startPolling();

        return () => {
          if (intervalId) clearInterval(intervalId);
        };
      }
    }, [currentOrder, alertShown])
  );

  if (!hasLocationPermission) {
    return (
      <View style={[commonStyles.container, { justifyContent: 'center', alignItems: 'center' }]}>
        <ActivityIndicator size="large" color="orange" />
        <Text style={{ marginTop: 10, color: 'orange', fontSize: 16 }}>Verifica dei permessi...</Text>
      </View>
    );
  }

  if (!userLocation) {
    return (
      <View style={[commonStyles.container, { justifyContent: 'center', alignItems: 'center' }]}>
        <ActivityIndicator size="large" color="orange" />
        <Text style={{ marginTop: 10, color: 'orange', fontSize: 16 }}>Verifica dei permessi...</Text>
      </View>
    );
  }

  return (
    <View style={commonStyles.mapContainer}>
      
      {/* Barra Superiore con la Scala */}
      <View style={commonStyles.mapOverlay}>
        <Text style={{ fontSize: 12, color: '#333' }}>🔍 Scala della mappa: 250m</Text>
      </View>

      <MapView
        style={commonStyles.map}
        region={{
          latitude: userLocation.latitude,
          longitude: userLocation.longitude,
          latitudeDelta: 0.1,
          longitudeDelta: 0.1,
        }}
      >
        {/* Marker Posizione Utente (🟦) */}
        <Marker coordinate={{ latitude: userLocation.latitude, longitude: userLocation.longitude }}>
          <Image source={require('./assets/position_marker.jpg')} style={commonStyles.mapMarkerImage} />
        </Marker>

        {/* Marker Posizione del Drone (🟢) */}
        {currentOrder && currentOrder.currentPosition && !orderCompleted && (
          <Marker coordinate={{ latitude: currentOrder.currentPosition.lat, longitude: currentOrder.currentPosition.lng }}>
            <Image source={require('./assets/green_marker.png')} style={commonStyles.mapMarkerImage} />
          </Marker>
        )}

        {/* Marker Posizione del Ristorante (🔴) */}
        {restaurant && (
          <Marker coordinate={{ latitude: restaurant.lat, longitude: restaurant.lng }}>
            <Image source={require('./assets/blue_marker.png')} style={commonStyles.mapMarkerImage} />
          </Marker>
        )}
      </MapView>
    </View>
  );
};

export default MapScreen;