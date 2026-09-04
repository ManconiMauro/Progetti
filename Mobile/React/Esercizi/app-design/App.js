import React, { useState } from 'react';
import { View, Text, TouchableOpacity } from 'react-native';
import ProfileScreen from './ProfileScreen';
import HomeScreen from './HomeScreen';
import commonStyles from './style/StyleSheet'; 

const App = () => {
  // Stato per tenere traccia della schermata corrente
  const [currentScreen, setCurrentScreen] = useState('Home');

  // Funzione per la navigazione
  const navigateTo = (screen) => {
    setCurrentScreen(screen);
  };

  return (
    <View style={commonStyles.appContainer}>
      <View style={commonStyles.screenContainer}>
      {currentScreen === 'Home' ? (
        <HomeScreen/>
      ) : (
        <ProfileScreen/>
      )}
      </View>

      <View style={commonStyles.navBar}>
      <TouchableOpacity 
          style={commonStyles.navButton} 
          onPress={() => navigateTo('Home')}>
          <Text style={commonStyles.navButtonText}>Home</Text>
        </TouchableOpacity>

        <TouchableOpacity 
          style={commonStyles.navButton} 
          onPress={() => navigateTo('Profile')}>
          <Text style={commonStyles.navButtonText}>Profilo</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
};

export default App;
