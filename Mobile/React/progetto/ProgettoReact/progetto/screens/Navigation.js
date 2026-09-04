import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import Icon from 'react-native-vector-icons/FontAwesome';
import MaterialIcons from 'react-native-vector-icons/MaterialIcons';
import HomeScreen from './HomeScreen';
import ProfileScreen from './ProfileScreen';
import MapScreen from './MapScreen';
import commonStyles from '../style/StyleSheet';

const Stack = createStackNavigator();
const Tab = createBottomTabNavigator();

const Navigation = ({ lastScreen, userLocation, buyMenu, order, handleNavigateToMap }) => {
  
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
              {...props}
              userLocation={userLocation}
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
            <MapScreen userLocation={userLocation} order={order} />
          )}
          listeners={({ navigation }) => ({
            tabPress: async (e) => {
              e.preventDefault();
              try {
                await handleNavigateToMap(navigation);
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
      <Stack.Navigator initialRouteName="Tabs" screenOptions={{ headerShown: false }}>
        <Stack.Screen name="Tabs" component={TabNavigator} />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default Navigation;
