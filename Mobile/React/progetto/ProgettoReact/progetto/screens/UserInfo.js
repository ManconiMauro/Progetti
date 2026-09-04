import React, { Component } from 'react';
import { View, Text, TextInput, TouchableOpacity, Alert, Keyboard, TouchableWithoutFeedback  } from 'react-native';
import CommunicationController from '../api/CommunicationController';
import commonStyles from '../style/StyleSheet';

class UserInfo extends Component {
  constructor(props) {
    super(props);

    this.state = {
      userInfo: { ...props.userInfo },
      fieldErrors: {},
    };
  }

  handleInputChange = (field, value) => {
    this.setState((prevState) => ({
      userInfo: {
        ...prevState.userInfo,
        [field]: value,
      },
    }), () => {
      // Validiamo ogni volta che l'utente modifica un campo
      this.validateField(field, value);
    });
  };

  validateField = (field, value) => {
    let isValid = true;

    if (!value) {
      isValid = false; // Campo vuoto
    } else if (field === 'cardNumber') {
      isValid = value.length === 16 && value.startsWith('123');
    } else if (field === 'cardExpireYear') {
      isValid = value.length === 4 && parseInt(value) > 2025;
    }

    this.setState(prevState => ({
      fieldErrors: {
        ...prevState.fieldErrors,
        [field]: !isValid,
      }
    }));
  };

  validateAllFields = () => {
    const { userInfo } = this.state;
    let errors = {};
    let isValid = true;

    Object.keys(userInfo).forEach(field => {
      if (!userInfo[field]) {
        errors[field] = true;
        isValid = false;
      }
    });
    
    if (userInfo.cardNumber && (!userInfo.cardNumber.startsWith('123') || userInfo.cardNumber.length !== 16)) {
      errors.cardNumber = true;
      isValid = false;
    }
    if (userInfo.cardExpireYear && (userInfo.cardExpireYear.length !== 4 || parseInt(userInfo.cardExpireYear) <= 2025)) {
      errors.cardExpireYear = true;
      isValid = false;
    }
    
    this.setState({ fieldErrors: errors });
    return isValid;
  };

  saveUserInfo = async () => {
    try {
      const { userInfo } = this.state;
      await CommunicationController.putUser(userInfo);

      // ✅ Mostra un alert di conferma
      Alert.alert("Successo", "Le informazioni sono state salvate correttamente.");

      // ✅ Aggiorna i dati nello stato del componente padre
      this.props.setUserInfo(userInfo);
    } catch (error) {
      console.error("Errore durante il salvataggio:", error);

      // ✅ Mostra un alert con il messaggio di errore
      Alert.alert(
        "Errore", 
        "Impossibile salvare le informazioni. Controlla i dati e riprova.",
        [{ text: "OK" }]
      );
    }
  };

  render() {
    const { userInfo, fieldErrors } = this.state;

    return (
      <TouchableWithoutFeedback onPress={Keyboard.dismiss} accessible={false}>
        <View style={commonStyles.userCard}>
          {['firstName', 'lastName', 'cardFullName', 'cardNumber', 'cardExpireMonth', 'cardExpireYear', 'cardCVV'].map(field => (
            <View key={field}>
              <Text style={commonStyles.label}>{field}</Text>
              <TextInput
                style={[commonStyles.input, { borderColor: fieldErrors[field] ? 'red' : 'gray' }]}
                value={userInfo[field]}
                onChangeText={(text) => this.handleInputChange(field, text)}
                keyboardType={field.includes('card') ? 'numeric' : 'default'}
              />
            </View>
          ))}

          <TouchableOpacity 
            style={commonStyles.button} 
            onPress={this.saveUserInfo}>
            <Text style={commonStyles.buttonText}>Salva</Text>
          </TouchableOpacity>
        </View>
      </TouchableWithoutFeedback>
    );
  }
}

export default UserInfo;