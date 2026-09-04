import React from 'react';
import { View, Text, Button, StyleSheet, Alert } from 'react-native';
import CommunicationController from '../api/CommunicationController';

const AbbonamentoMangione = ({ onBackClick }) => {
  const compraAbbonamento = async () => {
    try {
      await CommunicationController.putUserSubscription()
      Alert.alert(
        "Esame Febbraio:", 
        " acquisto abbonamento mangione andato a buon fine ",
        [{ text: "OK" }]
      );
    } catch (error) {
      console.log(error)
      Alert.alert(
        "Esame Febbraio:", 
        " acquisto abbonamento mangione non andato a buon fine ",
        [{ text: "OK" }]
      );
    }
    onBackClick
    return;
  }
  return (
    <View style={styles.container}>
      <Text style={styles.title}>Abbonamento Mangione</Text>
      <Text style={styles.text}>“Vuoi acquistare l’abbonamento mangione a 10€? La durata dell’abbonamento è di 30 giorni e ti darà accesso 
      a fantastici sconti su moltissimi menù.</Text>
      <Button title="Acquista Abbonamento" onPress={compraAbbonamento} />
      <Button title="Torna al Profilo" onPress={onBackClick} color="red" />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  title: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 20,
  },
  text: {
    fontSize: 14,
    marginBottom: 20,
  },
});

export default AbbonamentoMangione;
