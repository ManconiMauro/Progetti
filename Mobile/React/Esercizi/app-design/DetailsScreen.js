import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import commonStyles from './style/StyleSheet'; 

class DetailsScreen extends React.Component {
  render() {
    // Visualizziamo i dettagli del piatto in console per il debugging
    console.log(this.props.piatto);

    return (
    <View style={commonStyles.container}>
      <View style={commonStyles.dishContainer}>
        <Text style={commonStyles.title}>{this.props.piatto.name}</Text>
        <Text style={commonStyles.description}>{this.props.piatto.longDescription}</Text>

        <TouchableOpacity style={commonStyles.button} onPress={() => this.props.handleDelete()}>
          <Text style={commonStyles.buttonText}>Elimina Piatto</Text>
        </TouchableOpacity>

        <TouchableOpacity style={commonStyles.button} onPress={() => this.props.handleBack()}>
          <Text style={commonStyles.buttonText}>Indietro</Text>
        </TouchableOpacity>
      </View>
    </View>
    );
  }
}

export default DetailsScreen;
