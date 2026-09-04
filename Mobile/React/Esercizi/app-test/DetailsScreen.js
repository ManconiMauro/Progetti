import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';

class DetailsScreen extends React.Component {
  render() {
    // Visualizziamo i dettagli del piatto in console per il debugging
    console.log(this.props.piatto);

    return (
      <View style={styles.container}>
        <Text style={styles.title}>{this.props.piatto.name}</Text>
        <Text style={styles.description}>{this.props.piatto.longDescription}</Text>

        <TouchableOpacity style={styles.button} onPress={() => this.props.handleDelete()}>
          <Text style={styles.buttonText}>Elimina Piatto</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.button} onPress={() => this.props.handleBack()}>
          <Text style={styles.buttonText}>Indietro</Text>
        </TouchableOpacity>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 20,
  },
  description: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center',
    marginBottom: 20,
  },
  button: {
    backgroundColor: '#007BFF',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 5,
    marginVertical: 5,
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
    textAlign: 'center',
  },
});

export default DetailsScreen;
