import React, { useState, useEffect } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import HomeScreen from './HomeScreen';
import ProfileScreen from './ProfileScreen';
import MapScreen from './MapScreen';
import { Text, View, ActivityIndicator } from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';
import MaterialIcons from 'react-native-vector-icons/MaterialIcons';
import commonStyles from './style/StyleSheet';
import viewModel from './viewModel/viewModel';
import CommunicationController from './model/CommunicationController';

const Stack = createStackNavigator();
const Tab = createBottomTabNavigator();

const App = () => {
  const [initialScreen, setInitialScreen] = useState(false);
  const [lastScreen, setLastScreen] = useState("Home");
  const [userLocation, setUserLocation] = useState(null);
  const [order, setOrder] = useState(null);

  const retrieveLastScreen = async () => {
    try {
      const lastScreen = await AsyncStorage.getItem('lastScreen');
      setLastScreen(lastScreen || 'Home'); // Se non esiste, di default parte da Home
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
      console.log("Utente creato oppure già loggato")
      checkAndgetUserLocation()
    } catch (error) {
      console.error('Errore durante il controllo delle credenziali:', error);
    }
  };

  const checkAndgetUserLocation = async () => {
    let userLocation = await viewModel.locationPermission();
    setUserLocation(userLocation);
    console.log("Location in App: ",userLocation);
    setInitialScreen(true);
  }

  // Funzione per caricare dati e navigare a MapScreen
  const handleNavigateToMap = async (navigation) => {
    try {
      if(userLocation){
        const data = await viewModel.locationPermission();
        setUserLocation(data);
      }
      console.log(userLocation)
      
      navigation.navigate('Map'); 
    } catch (error) {
      console.error('Errore durante il caricamento dei dati per la mappa:', error);
    }
  };

  const buyMenu = async (navigation, piatto) => {
    try {
      const user = await CommunicationController.getUser()
      if(user == null){
        Alert.alert(
          "Ordine in Corso", 
          "I Dati del Profilo non sono stati inseriti!",
          [{ text: "OK" }]
        );
        return;
      }else if(user.orderStatus=="ON_DELIVERY"){
        Alert.alert(
          "Ordine in Corso", 
          "Hai già un ordine in corso, non puoi fare un nuovo ordine finché non viene consegnato.",
          [{ text: "OK" }]
        );
        return;
      }else {
        const data = await viewModel.makeOrder(piatto.mid);
        console.log(data.userLocation, data.order)
        await AsyncStorage.setItem('restaurant', JSON.stringify(piatto.location));
        setUserLocation(data.userLocation);
        setOrder(data.order)

        navigation.navigate('Map');
      }
    } catch (error) {
      console.error('Errore durante il caricamento dei dati per la mappa:', error);
    }
  } 

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

  // Funzione per il TabNavigator
  const TabNavigator = () => {
    return (
      <Tab.Navigator
      initialRouteName={lastScreen}
      screenOptions={{
        tabBarStyle: {
          backgroundColor: '#fff',
          borderTopLeftRadius: 20,
          borderTopRightRadius: 20,
          height: 70,
        },
        tabBarLabelStyle: {
          fontSize: 12,
          fontWeight: 'bold',
          color: '#333',
        },
        tabBarActiveTintColor: commonStyles.tabBarActiveTintColor,
        tabBarInactiveTintColor: commonStyles.tabBarInactiveTintColor,
      }}
      >
        <Tab.Screen
          name="Home"
          children={(props) => (
          <HomeScreen 
            {...props} // Passa tutte le props di React Navigation
            userLocation={userLocation} // Altre props specifiche di App
            buyMenu={buyMenu}
          />
          )}
          options={{
          tabBarLabel: 'Home',
          tabBarIcon: ({ color, size }) => <Icon name="home" size={size} color={color} />,
          }}
        />
        <Tab.Screen
          name="Map"
          children={() => (
            <MapScreen userLocation={userLocation} order={order}/>
          )}
          listeners={({ navigation }) => ({
            tabPress: async (e) => {
              e.preventDefault(); // Evita la navigazione predefinita

              try {
                await handleNavigateToMap(navigation); // Recupera i dati necessari
              } catch (error) {
                console.error("Errore durante il caricamento dei dati per la mappa:", error);
              }
            },
          })}
          options={{
            tabBarLabel: 'Mappa',
            tabBarIcon: ({ color, size }) => <MaterialIcons name="location-on" size={size} color={color} />,
          }}
        />
        <Tab.Screen
          name="Profile"
          component={ProfileScreen}
          options={{
            tabBarLabel: 'Profilo',
            tabBarIcon: ({ color, size }) => <Icon name="user" size={size} color={color} />,
          }}
        />
      </Tab.Navigator>
    );
  };

  return (
    <NavigationContainer>
      <Stack.Navigator
        initialRouteName="Tabs" 
        screenOptions={{
          headerShown: false, // Nascondi l'header per il tab navigator
        }}
      >
        <Stack.Screen
          name="Tabs"
          component={TabNavigator} 
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default App;