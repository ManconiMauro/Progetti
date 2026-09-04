import React from 'react';
import { View, Text, TouchableOpacity, Image } from 'react-native';
import commonStyles from './style/StyleSheet'; 

class DetailScreen extends React.Component {
  render() {
    return (
      <View style={commonStyles.detailContainer}>
        <Text style={commonStyles.title}>{this.props.piatto.name}</Text>
        
        <View style={{ alignItems: 'center' }}>
          <Image
            source={{ uri: `data:image/png;base64,${this.props.piatto.base64}` }}
            style={commonStyles.image}
          />
        </View>

        <Text style={commonStyles.description}>{this.props.piatto.longDescription}</Text>

        <Text style={commonStyles.price}>Prezzo: {this.props.piatto.price}€</Text>

        <View style={commonStyles.buttonContainer}>
          <TouchableOpacity style={commonStyles.button} onPress={() => this.props.buyMenu(this.props.navigation, this.props.piatto)}>
            <Text style={commonStyles.buttonText}>Ordina questo menu</Text>
          </TouchableOpacity>

          <TouchableOpacity style={commonStyles.button} onPress={() => this.props.handleBack()}>
            <Text style={commonStyles.buttonText}>Torna Indietro</Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  }
}

export default DetailScreen;