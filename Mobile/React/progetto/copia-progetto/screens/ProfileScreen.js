import React, { Component } from 'react';
import { View, ActivityIndicator, Text, TouchableOpacity } from 'react-native';
import viewModel from '../viewModel/viewModel';
import commonStyles from '../style/StyleSheet';
import UserInfo from './UserInfo';
import AsyncStorage from '@react-native-async-storage/async-storage';

class ProfileScreen extends Component {
  constructor(props) {
    super(props);
    this.state = {
      userInfo: null, // Inizialmente nullo, perché i dati non sono ancora stati caricati
      isLoading: true, // Stato di caricamento
      subscription: false,
    };
  }

  async componentDidMount() {
    try {
      AsyncStorage.setItem('lastScreen', 'Profile').catch(error => console.log("Errore nel salvataggio: ", error));
      const data = await viewModel.fetchUser();
      if (data) {
        console.log(data);
        if(data.subscription){
          console.log("Esame febbraio: l’utente ha un abbonamento mangione ")
          this.setState({ userInfo: data, isLoading: false, subscription: true }); // Dati caricati, fine del caricamento
        }else{
          console.log("Esame febbraio: l’utente non ha un abbonamento mangione ")
          this.setState({ userInfo: data, isLoading: false, subscription: false  });
        }
      } else {
        console.log("Failed to fetch user data");
        this.setState({ isLoading: false }); // Fine del caricamento anche in caso di errore
      }
    } catch (error) {
      console.error("Error loading user data:", error);
      this.setState({ isLoading: false }); // Fine del caricamento in caso di errore
    }
  }

  render() {
    const { userInfo, isLoading, subscription } = this.state;

    if (isLoading) {
      return (
        <View style={commonStyles.profileContainer}>
          <ActivityIndicator size="large" color="#007bff" />
          <Text style={{ color: '#fff', marginTop: 10 }}>Caricamento dati...</Text>
        </View>
      );
    }

    if (!userInfo) {
      return (
        <View style={commonStyles.profileContainer}>
          <Text style={{ color: '#fff', marginTop: 10 }}>Errore nel caricamento dei dati.</Text>
        </View>
      );
    }

    if(!subscription){
      return (
        <View style={commonStyles.profileContainer}>
          <UserInfo
            userInfo={userInfo}
            setUserInfo={(updatedInfo) =>
              this.setState({ userInfo: { ...userInfo, ...updatedInfo } })
            }
          />
          <TouchableOpacity 
            style={commonStyles.button} 
            onPress={() => this.props.navigation.navigate('Subscription')}>
            <Text style={commonStyles.buttonText}>Compra Abbonamento</Text>
          </TouchableOpacity>
        </View>
      );
    }else{
      return (
        <View style={commonStyles.profileContainer}>
          <UserInfo
            userInfo={userInfo}
            setUserInfo={(updatedInfo) =>
              this.setState({ userInfo: { ...userInfo, ...updatedInfo } })
            }
          />
          <Text style={commonStyles.description}>Hai già l’abbonamento mangione</Text>
        </View>
      );
    }
  }
}

export default ProfileScreen;