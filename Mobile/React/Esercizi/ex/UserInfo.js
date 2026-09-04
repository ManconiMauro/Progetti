import React, { useState } from 'react';
import { View, Text, TextInput, Button, StyleSheet } from 'react-native';
import CommunicationController from './model/CommunicationController';

const UserCard = ({ userInfo, setUserInfo }) => {
  const [editable, setEditable] = useState(false);

  const toggleEdit = async () => {
    if (editable) {
      // Save data if we're switching from edit mode to view mode
      await saveUserInfo();
    }
    setEditable(!editable);
  };

  const handleInputChange = (field, value) => {
    setUserInfo({ ...userInfo, [field]: value });
  };

  // Function to save user data
  const saveUserInfo = async () => {
    try {
      // Call CommunicationController to update user info
      await CommunicationController.putUser(userInfo);
      console.log("User info saved successfully");
    } catch (error) {
      console.error("Error saving user info:", error);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.label}>Nome</Text>
      <TextInput
        style={styles.input}
        value={userInfo.firstName}
        editable={editable}
        onChangeText={(text) => handleInputChange('firstName', text)}
      />

      <Text style={styles.label}>Cognome</Text>
      <TextInput
        style={styles.input}
        value={userInfo.lastName}
        editable={editable}
        onChangeText={(text) => handleInputChange('lastName', text)}
      />

      <Text style={styles.label}>Nome sulla Carta</Text>
      <TextInput
        style={styles.input}
        value={userInfo.cardFullName}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardFullName', text)}
      />

      <Text style={styles.label}>Numero della Carta</Text>
      <TextInput
        style={styles.input}
        value={userInfo.cardNumber}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardNumber', text)}
        keyboardType="numeric"
      />

      <Text style={styles.label}>Mese di Scadenza</Text>
      <TextInput
        style={styles.input}
        value={userInfo.cardExpireMonth}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardExpireMonth', text)}
        keyboardType="numeric"
      />

      <Text style={styles.label}>Anno di Scadenza</Text>
      <TextInput
        style={styles.input}
        value={userInfo.cardExpireYear}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardExpireYear', text)}
        keyboardType="numeric"
      />

      <Text style={styles.label}>CVV</Text>
      <TextInput
        style={styles.input}
        value={userInfo.cardCVV}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardCVV', text)}
        keyboardType="numeric"
        secureTextEntry
      />

      <Button title={editable ? 'Salva' : 'Modifica'} onPress={toggleEdit} />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 20,
    backgroundColor: '#f5f5f5',
  },
  label: {
    fontSize: 16,
    fontWeight: 'bold',
    marginTop: 10,
  },
  input: {
    borderWidth: 1,
    borderColor: '#ddd',
    padding: 10,
    borderRadius: 5,
    marginTop: 5,
  },
});

export default UserCard;
