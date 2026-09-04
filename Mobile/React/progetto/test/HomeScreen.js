import React, { Component } from 'react';
import { View } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import ListScreen from './ListScreen';
import DetailScreen from './DetailScreen';
import commonStyles from './style/StyleSheet';
import viewModel from './viewModel/viewModel';

class HomeScreen extends Component {
  state = {
    menuItems: [], // Inizializza come array vuoto
    screen: 'List', // Schermo iniziale
    dish: null, // Oggetto piatto selezionato
  };

  async componentDidMount() {
    this.getMenus();
  }

  async getMenus() {
    try {
      console.log("Caricamento dei menu in corso...");
      const data = await viewModel.fetchData(); // Fetch dei dati dal viewModel

      this.setState({ menuItems: data }); // Salva i dati come array nello stato
    } catch (error) {
      console.error('Errore nel caricamento dei dati:', error);
    }
  }

  handleDetails = async (dish) => {
    this.setState({ screen: 'Detail', dish }); // Cambia schermo e salva il piatto selezionato
    await AsyncStorage.setItem('secondaryScreen', 'Detail');
    await AsyncStorage.setItem('dish', JSON.stringify(dish)); // Serializza il dato
  };

  handleBack = async () => {
    this.setState({ screen: 'List' }); // Torna alla lista
    await AsyncStorage.setItem('secondaryScreen', 'List');
  };

  render() {
    const { menuItems, screen, dish } = this.state;

    const menuItemsWithoutBase64 = menuItems.map(({ base64, ...rest }) => rest);
    console.log("MenuItems passati a ListScreen (senza base64):", menuItemsWithoutBase64);

    return (
      <View style={commonStyles.appContainer}>
        <View style={commonStyles.screenContainer}>
          {screen === 'List' ? (
            <View style={commonStyles.homeContainer}>
              <ListScreen
                dishes={menuItems} // Passa l'array di piatti
                handleDetails={this.handleDetails} // Passa la funzione per gestire i dettagli
              />
            </View>
          ) : (
            <View style={commonStyles.homeContainer}>
              <DetailScreen
                piatto={dish} // Passa il piatto selezionato
                handleBack={this.handleBack} // Passa la funzione per tornare indietro
              />
            </View>
          )}
        </View>
      </View>
    );
  }
}

export default HomeScreen;
