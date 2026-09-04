import React, { Component } from 'react';
import { View, Text, TextInput, TouchableOpacity, Alert, Keyboard, TouchableWithoutFeedback  } from 'react-native';
import CommunicationController from './model/CommunicationController';
import commonStyles from './style/StyleSheet';

class UserInfo extends Component {
  constructor(props) {
    super(props);

    this.state = {
      userInfo: { ...props.userInfo },
      // Stati di validità per i campi
      isCardNumberValid: true,
      isCardExpireMonthValid: true,
      isCardExpireYearValid: true,
      isCvvValid: true,
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
    // Validazione del numero carta
    if (field === 'cardNumber') {
      this.setState({
        isCardNumberValid: value.length === 16,
      });
    }
    // Validazione del mese di scadenza
    if (field === 'cardExpireMonth') {
      this.setState({
        isCardExpireMonthValid: parseInt(value) >= 1 && parseInt(value) <= 12,
      });
    }
    // Validazione dell'anno di scadenza (anno maggiore o uguale a 25)
    if (field === 'cardExpireYear') {
      this.setState({
        isCardExpireYearValid: parseInt(value) >= 25,
      });
    }
    // Validazione del CVV
    if (field === 'cardCVV') {
      this.setState({
        isCvvValid: value.length === 3,
      });
    }
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
    const { userInfo, isCardNumberValid, isCardExpireMonthValid, isCardExpireYearValid, isCvvValid } = this.state;
    console.log("Dati passati a UserInfo:", this.state.userInfo);

    return (
      <TouchableWithoutFeedback onPress={Keyboard.dismiss} accessible={false}>
      <View style={commonStyles.userCard}>
        <Text style={commonStyles.label}>Nome</Text>
        <TextInput
          style={commonStyles.input}
          value={userInfo.firstName}
          onChangeText={(text) => this.handleInputChange('firstName', text)}
        />

        <Text style={commonStyles.label}>Cognome</Text>
        <TextInput
          style={commonStyles.input}
          value={userInfo.lastName}
          onChangeText={(text) => this.handleInputChange('lastName', text)}
        />

        <Text style={commonStyles.label}>Nome sulla Carta</Text>
        <TextInput
          style={commonStyles.input}
          value={userInfo.cardFullName}
          onChangeText={(text) => this.handleInputChange('cardFullName', text)}
        />

        <Text style={commonStyles.label}>Numero della Carta</Text>
        <TextInput
          style={[
            commonStyles.input,
            { borderColor: isCardNumberValid ? 'gray' : 'red' }, // Cambia il colore del bordo
          ]}
          value={userInfo.cardNumber}
          onChangeText={(text) => this.handleInputChange('cardNumber', text)}
          keyboardType="numeric"
        />

        <View style={commonStyles.dateContainer}>
          <TextInput
            style={[commonStyles.halfInput, 
              { marginRight: 10, borderColor: isCardExpireMonthValid ? '#D1D1D1' : 'red' }
            ]}
            placeholder="MM"
            value={userInfo.cardExpireMonth}
            onChangeText={(text) => this.handleInputChange('cardExpireMonth', text)}
            keyboardType="numeric"
          />
          <TextInput
            style={[
              commonStyles.halfInput,
              { borderColor: isCardExpireYearValid ? '#D1D1D1' : 'red' }
            ]}
            placeholder="YY"
            value={userInfo.cardExpireYear}
            onChangeText={(text) => this.handleInputChange('cardExpireYear', text)}
            keyboardType="numeric"
          />
        </View>

        <Text style={commonStyles.label}>CVV</Text>
        <TextInput
          style={[
            commonStyles.input,
            { borderColor: isCvvValid ? 'gray' : 'red' }, // Cambia il colore del bordo
          ]}
          value={userInfo.cardCVV}
          onChangeText={(text) => this.handleInputChange('cardCVV', text)}
          keyboardType="numeric"
          secureTextEntry
        />

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