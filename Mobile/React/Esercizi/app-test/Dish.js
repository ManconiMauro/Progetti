import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';

class Dish extends React.Component { 
  render() {
    return (
      <View style={styles.container}>
        <Text style={styles.name}>{this.props.data.name}</Text>
        <Text style={styles.description}>{this.props.data.shortDescription}</Text>
        <TouchableOpacity
          style={styles.button}
          onPress={() => this.props.handleDetails(this.props.data)}
        >
          <Text style={styles.buttonText}>Dettagli</Text>
        </TouchableOpacity>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    padding: 10,
    marginBottom: 10,
    backgroundColor: '#f9f9f9',
    borderRadius: 5,
    borderWidth: 1,
    borderColor: '#ddd',
  },
  name: {
    fontSize: 18,
    fontWeight: 'bold',
  },
  description: {
    fontSize: 14,
    color: '#666',
    marginBottom: 10,
  },
  button: {
    backgroundColor: '#007BFF',
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderRadius: 5,
  },
  buttonText: {
    color: '#fff',
    fontWeight: 'bold',
    textAlign: 'center',
  },
});

export default Dish;
