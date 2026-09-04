import { StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import * as Location from 'expo-location';
import MapView, { Marker } from 'react-native-maps';
import { useState, useEffect } from 'react';

export default function App() {
  const [location, setLocation] = useState(null); 
  const [permission, setPermission] = useState(false);

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

    if (canUseLocation) {
      const userLocation = await Location.getCurrentPositionAsync();
      setLocation(userLocation.coords);
    }
  };

  useEffect(() => {
    if (permission) {
      const interval = setInterval(async () => {
        const userLocation = await Location.getCurrentPositionAsync();
        setLocation(userLocation.coords);
      }, 2000); 

      return () => clearInterval(interval);
    }
  }, [permission]); 

  if (!permission) {
    return (
      <View style={styles.container}>
        <TouchableOpacity onPress={locationPermissionAsync} style={styles.button}>
          <Text style={styles.buttonText}>Premi per visualizzare la mappa</Text>
        </TouchableOpacity>
      </View>
    );
  }

  if (location) {
    return (
      <View style={styles.container}>
        <MapView
          style={styles.map}
          initialRegion={{
            latitude: location.latitude,
            longitude: location.longitude,
            latitudeDelta: 0.1,
            longitudeDelta: 0.1,
          }}
        >
          <Marker
            coordinate={{
              latitude: location.latitude,
              longitude: location.longitude,
            }}
            title="La tua posizione"
            description="Questa è la tua posizione attuale"
          />
        </MapView>
      </View>
    );
  }

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
  map: {
    width: '100%',
    height: '100%',
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
