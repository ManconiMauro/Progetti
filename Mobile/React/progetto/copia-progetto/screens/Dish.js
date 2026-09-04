import React from 'react';
import { View, Text, TouchableOpacity, Image } from 'react-native';
import commonStyles from '../style/StyleSheet';
import viewModel from '../viewModel/viewModel';

class Dish extends React.Component {
  state = {
    dish: this.props.data,
  };

  async componentDidMount() {
    try {
      const dishDetails = await viewModel.fetchDishDetails(
        this.props.data.mid, 
        this.props.data.location.lat, 
        this.props.data.location.lng
      );

      console.log('Dati piatto singolo:', dishDetails);

      this.setState((prevState) => ({
        dish: {
          ...prevState.dish,
          ...dishDetails,
        },
      }));
    } catch (error) {
      console.error('Errore durante il caricamento dei dettagli del piatto:', error);
    }
  }

  render() {
    const { dish } = this.state;
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
          {dish.discount ? (
            <Text style={commonStyles.price}>
              😊 Prezzo scontato di {dish.discount}€
            </Text>
          ) : dish.missedDiscount ? (
            <Text style={commonStyles.price}>
              😞 Non hai diritto allo sconto di {dish.missedDiscount}€
            </Text>
          ) : (
            <Text style={commonStyles.price}>{dish.price}€</Text>
          )}
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