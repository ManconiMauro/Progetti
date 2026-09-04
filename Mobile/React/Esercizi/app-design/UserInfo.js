import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity } from 'react-native';
import CommunicationController from './model/CommunicationController';
import commonStyles from './style/StyleSheet'; 

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
    <View style={commonStyles.userCard}>
      <Text style={commonStyles.label}>Nome</Text>
      <TextInput
        style={commonStyles.input}
        value={userInfo.firstName}
        editable={editable}
        onChangeText={(text) => handleInputChange('firstName', text)}
      />

      <Text style={commonStyles.label}>Cognome</Text>
      <TextInput
        style={commonStyles.input}
        value={userInfo.lastName}
        editable={editable}
        onChangeText={(text) => handleInputChange('lastName', text)}
      />

      <Text style={commonStyles.label}>Nome sulla Carta</Text>
      <TextInput
        style={commonStyles.input}
        value={userInfo.cardFullName}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardFullName', text)}
      />

      <Text style={commonStyles.label}>Numero della Carta</Text>
      <TextInput
        style={commonStyles.input}
        value={userInfo.cardNumber}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardNumber', text)}
        keyboardType="numeric"
      />

      <Text style={commonStyles.label}>Mese di Scadenza</Text>
      <TextInput
        style={commonStyles.input}
        value={userInfo.cardExpireMonth}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardExpireMonth', text)}
        keyboardType="numeric"
      />

      <Text style={commonStyles.label}>Anno di Scadenza</Text>
      <TextInput
        style={commonStyles.input}
        value={userInfo.cardExpireYear}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardExpireYear', text)}
        keyboardType="numeric"
      />

      <Text style={commonStyles.label}>CVV</Text>
      <TextInput
        style={commonStyles.input}
        value={userInfo.cardCVV}
        editable={editable}
        onChangeText={(text) => handleInputChange('cardCVV', text)}
        keyboardType="numeric"
        secureTextEntry
      />

<TouchableOpacity 
      style={commonStyles.button} 
      onPress={toggleEdit}>
      <Text style={commonStyles.buttonText}>
        {editable ? 'Salva' : 'Modifica'}
      </Text>
    </TouchableOpacity>
    </View>
  );
};

export default UserCard;
