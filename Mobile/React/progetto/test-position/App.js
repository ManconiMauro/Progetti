import React, { useState } from 'react';
import { StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import * as Location from 'expo-location';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import MapComponent from './MapComponent';
import CommunicationController from './model/CommunicationControlle';

// Creazione dello Stack Navigator
const Stack = createStackNavigator();

export default function App() {
  const [userLocation, setUserLocation] = useState(null);
  const [permission, setPermission] = useState(false);
  const [order, setOrder] = useState(null);

  const locationPermissionAsync = async () => {
    let canUseLocation = false;

    const grantedPermission = await Location.getForegroundPermissionsAsync();
    if (grantedPermission.status === 'granted') {
      canUseLocation = true;
      setPermission(true);
    } else {
      const permissionResponse = await Location.requestForegroundPermissionsAsync();
      if (permissionResponse.status === 'granted') {
        canUseLocation = true;
        setPermission(true);
      }
    }

    console.log('Permessi Controllati');
    if (canUseLocation) {
      console.log('Ho i permessi');
      const userLocation = await Location.getCurrentPositionAsync();
      setUserLocation(userLocation.coords);
      console.log('Presa e salvata la posizione dell\'utente');
      let user = await CommunicationController.getUser();
      if (user.orderStatus === 'ON_DELIVERY') {
        let order = await CommunicationController.getOrder(user.lastOid);
        setOrder(order);
        console.log("L'utente aveva già un ordine attivo, trovati questi dati: ", order);
      } else {
        let order = await CommunicationController.buyMenu(12, userLocation.coords);
        setOrder(order);
        console.log('Abbiamo fatto l\'ordine di un menù e abbiamo salvato questi dati: ', order);
      }
    }
  };

  // Se l'utente non ha dato i permessi, mostriamo un pulsante per richiederli
  if (!permission) {
    return (
      <View style={styles.container}>
        <TouchableOpacity onPress={locationPermissionAsync} style={styles.button}>
          <Text style={styles.buttonText}>Premi per visualizzare la mappa</Text>
        </TouchableOpacity>
      </View>
    );
  }

  // Quando la posizione e l'ordine sono disponibili, passiamo a MapComponent
  if (userLocation && order) {
    return (
      <NavigationContainer>
        <Stack.Navigator initialRouteName="Home">
          <Stack.Screen name="Home">
            {() => (
              <MapComponent userLocation={userLocation} order={order} />
            )}
          </Stack.Screen>
        </Stack.Navigator>
      </NavigationContainer>
    );
  }

  // Durante il caricamento della posizione, mostriamo un messaggio
  return (
    <View style={styles.container}>
      <Text>Caricamento della posizione...</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
  },
  button: {
    backgroundColor: '#007bff',
    padding: 12,
    borderRadius: 8,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
  },
});
