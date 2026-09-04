import React, { Component } from 'react';
import { StyleSheet, View, Text } from 'react-native';
import MapView, { Marker } from 'react-native-maps';
import CommunicationController from './model/CommunicationControlle';

class MapComponent extends Component {
  constructor(props) {
    super(props);
    this.state = {
      order: props.order,
      orderCompleted: false,
    };
  }

  componentDidMount() {
    // Inizia il polling per aggiornare la posizione del drone
    this.interval = setInterval(async () => {
      try {
        const currentOrder = await CommunicationController.getOrder(this.state.order.oid);
        console.log('Stato dell\'ordine:', currentOrder.status);

        // Aggiorna lo stato dell'ordine
        this.setState((prevState) => ({
          order: {
            ...prevState.order,
            currentPosition: currentOrder.currentPosition,
            status: currentOrder.status,
          },
          orderCompleted: currentOrder.status === 'COMPLETED', // Aggiorna lo stato di completamento
        }));

        // Interrompi il polling se l'ordine è completato
        if (currentOrder.status === 'COMPLETED') {
          clearInterval(this.interval);
          this.state.orderCompleted = true;
        }
      } catch (error) {
        console.error('Errore durante l\'aggiornamento della posizione:', error);
      }
    }, 2000);
  }

  componentWillUnmount() {
    // Ferma il polling quando il componente viene smontato
    clearInterval(this.interval);
  }

  render() {
    const { userLocation } = this.props;
    const { order, orderCompleted } = this.state;

    return (
      <View style={styles.container}>
        {orderCompleted && (
          <View style={styles.messageContainer}>
            <Text style={styles.messageText}>L'ordine è stato completato!</Text>
          </View>
        )}

        <MapView
          style={styles.map}
          initialRegion={{
            latitude: userLocation.latitude,
            longitude: userLocation.longitude,
            latitudeDelta: 0.1,
            longitudeDelta: 0.1,
          }}
        >
          {/* Marcatore per la posizione dell'utente */}
          <Marker
            coordinate={{
              latitude: userLocation.latitude,
              longitude: userLocation.longitude,
            }}
            title="La tua posizione"
            description="Questa è la tua posizione attuale"
          />

          {/* Mostra il marcatore del drone solo se l'ordine è in consegna */}
          {!orderCompleted && order.currentPosition && (
            <Marker
              coordinate={{
                latitude: order.currentPosition.lat,
                longitude: order.currentPosition.lng,
              }}
              title="Posizione Drone"
              description="Questa è la posizione attuale del drone"
              pinColor="blue"
            />
          )}
        </MapView>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  map: {
    width: '100%',
    height: '100%',
  },
  messageContainer: {
    position: 'absolute',
    top: 20,
    alignSelf: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    padding: 12,
    borderRadius: 8,
  },
  messageText: {
    color: 'white',
    fontSize: 16,
    fontWeight: 'bold',
  },
});

export default MapComponent;