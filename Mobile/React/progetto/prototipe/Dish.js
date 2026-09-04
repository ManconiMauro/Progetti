import React from 'react';
import { View, Text, TouchableOpacity, Image } from 'react-native';
import commonStyles from './style/StyleSheet';
import viewModel from './viewModel/viewModel';

class Dish extends React.Component {
  state = {
    dish: this.props.data,
  };

  async componentDidMount() {
    try {
      // Carica i dettagli aggiuntivi del piatto
      const dishDetails = await viewModel.fetchDishDetails(
        this.props.data.mid, // ID del piatto
        this.props.data.location.lat, // Latitudine
        this.props.data.location.lng // Longitudine
      );

      console.log('Dati piatto singolo:', dishDetails);

      // Unisci i dati esistenti con quelli caricati
      this.setState((prevState) => ({
        dish: {
          ...prevState.dish, // Mantieni i dati esistenti (come `base64`)
          ...dishDetails, // Sovrascrivi o aggiungi nuovi dettagli
        },
      }));
    } catch (error) {
      console.error('Errore durante il caricamento dei dettagli del piatto:', error);
    }
  }

  render() {
    const { dish } = this.state; // Ottieni il piatto dallo stato
    const { handleDetails } = this.props;

    return (
      <TouchableOpacity
        style={commonStyles.dishContainer}
        onPress={() => handleDetails(this.state.dish)}
      >
        {/* Nome del piatto */}
        <Text style={commonStyles.name}>{dish.name}</Text>

        {/* Contenitore per immagine e prezzo */}
        <View style={commonStyles.imagePriceContainer}>
          <Image
            source={{ uri: `data:image/png;base64,${dish.base64}` }}
            style={commonStyles.image}
          />
          <Text style={commonStyles.price}>{dish.price}€</Text>
        </View>

        {/* Descrizione breve */}
        <Text style={commonStyles.description}>
          {dish.shortDescription || 'Descrizione non disponibile'}
        </Text>
      </TouchableOpacity>
    );
  }
}

export default Dish;