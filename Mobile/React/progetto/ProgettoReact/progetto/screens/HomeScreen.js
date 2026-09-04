import React, { Component } from 'react';
import { View } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import ListScreen from './ListScreen';
import DetailScreen from './DetailScreen';
import commonStyles from '../style/StyleSheet';
import viewModel from '../viewModel/viewModel';

class HomeScreen extends Component {
  state = {
    menuItems: [], // Inizializza come array vuoto
    screen: 'List', // Schermo iniziale
    dish: null, // Oggetto piatto selezionato
  };

  async componentDidMount() {
    // Controlla se lo stato contiene già i dati del menu
    if (!this.state.menuItems || this.state.menuItems.length === 0) {
        console.log("Caricamento menu...");
      try {
        // Se lo stato è vuoto, chiama la funzione per ottenere i dati
        await this.getMenus();
      } catch (error) {
        console.error('Errore durante il caricamento del menu:', error);
      }
    } else {
        console.log("Menu già caricati, salto il caricamento.");
        console.log('Menu items:', this.state.menuItems);
    }
  }
  

  async getMenus() {
    try {
      console.log("Caricamento dei menu in corso...");
      const data = await viewModel.fetchData(this.props.userLocation); 

      this.setState({ menuItems: data }); // Salva i dati come array nello stato
    } catch (error) {
      console.error('Errore nel caricamento dei dati:', error);
    }
  }

  handleDetails = async (dish) => {
    try {
      // Verifica se il piatto ha già tutte le informazioni necessarie
      if (!dish.shortDescription || !dish.longDescription) {
        console.log('Caricamento dettagli mancanti per il piatto...');
        const fullDish = await viewModel.fetchDishDetails(dish.mid, dish.location.lat, dish.location.lng);
        
        // Unisci i dati esistenti con quelli recuperati
        dish = { ...dish, ...fullDish };
      }
  
      this.setState({ screen: 'Detail', dish });
  
      await AsyncStorage.setItem('secondaryScreen', 'Detail');
      await AsyncStorage.setItem('dish', JSON.stringify(dish)); // Serializza il dato
    } catch (error) {
      console.error('Errore durante il caricamento dei dettagli del piatto:', error);
    }
  };
  

  handleBack = async () => {
    this.setState({ screen: 'List' }); // Torna alla lista
    await AsyncStorage.setItem('secondaryScreen', 'List');
  };

  render() {
    const { menuItems, screen, dish } = this.state;

    return (
      <View style={commonStyles.appContainer}>
        <View style={commonStyles.screenContainer}>
          {screen === 'List' ? (
            <View style={commonStyles.homeContainer}>
              <ListScreen
                dishes={menuItems} // Passa l'array di piatti
                handleDetails={this.handleDetails}
              />
            </View>
          ) : (
            <View style={commonStyles.homeContainer}>
              <DetailScreen
                piatto={dish} // Passa il piatto selezionato
                handleBack={this.handleBack} 
                buyMenu={this.props.buyMenu}
                navigation={this.props.navigation}
              />
            </View>
          )}
        </View>
      </View>
    );
  }
}

export default HomeScreen;