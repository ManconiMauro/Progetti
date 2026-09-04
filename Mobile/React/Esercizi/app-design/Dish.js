import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import commonStyles from './style/StyleSheet'; 

class Dish extends React.Component { 
  render() {
    return (
      <View style={commonStyles.dishContainer}>
        <Text style={commonStyles.name}>{this.props.data.name}</Text>
        <Text style={commonStyles.description}>{this.props.data.shortDescription}</Text>
        <TouchableOpacity
          style={commonStyles.button}
          onPress={() => this.props.handleDetails(this.props.data)}
        >
          <Text style={commonStyles.buttonText}>Dettagli</Text>
        </TouchableOpacity>
      </View>
    );
  }
}

export default Dish;
