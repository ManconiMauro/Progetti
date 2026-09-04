import React, { useEffect, useState } from 'react'; 
import { View, FlatList, ActivityIndicator, Alert, Text } from 'react-native';
import Dish from './Dish';
import commonStyles from '../style/StyleSheet';
import AsyncStorage from '@react-native-async-storage/async-storage';
import viewModel from '../viewModel/viewModel';

const ListScreen = ({ dishes, handleDetails }) => {
  const [hasLocationPermission, setHasLocationPermission] = useState(false);

  useEffect(() => {
    const checkLocationAccess = async () => {
      const permissionGranted = await viewModel.checkPermission();
      setHasLocationPermission(permissionGranted);

      if (!permissionGranted) {
        Alert.alert(
          "Permessi di posizione necessari",
          "Abilita la posizione per accedere alla lista dei piatti.",
          [{ text: "OK" }]
        );
      } 
      AsyncStorage.setItem('lastScreen', 'Home').catch(error => console.log("Errore nel salvataggio: ", error));
    };

    checkLocationAccess();
  }, []);

  // Se i permessi sono negati, mostriamo solo l'indicatore di caricamento e un messaggio
  if (!hasLocationPermission) {
    return (
      <View style={[commonStyles.container, { justifyContent: 'center', alignItems: 'center' }]}>
        <ActivityIndicator size="large" color="orange" />
        <Text style={{ marginTop: 10, color: 'orange', fontSize: 16 }}>Permessi di posizione necessari!</Text>
      </View>
    );
  }

  return (
    <View style={[commonStyles.listContainer]}>
      <FlatList
        data={dishes}
        renderItem={({ item }) => (
          <Dish
            key={item.id}
            data={item}
            handleDetails={() => handleDetails(item)}
          />
        )}
        keyExtractor={(item) => item.mid.toString()}
        contentContainerStyle={commonStyles.flatListContent} 
      />
    </View>
  );
};

export default ListScreen;
