import React, { Component } from 'react';
import { View, StyleSheet } from 'react-native';
import ListScreen from './ListScreen';
import DetailsScreen from './DetailsScreen';

class App extends Component { 
  state = { 
    screen: true, // True per la schermata lista, false per la schermata dettagli
    dishes: [
      {
          id: 1,
          name: "Spaghetti Carbonara",
          shortDescription: "Un classico piatto italiano a base di uova, pancetta e pecorino.",
          longDescription: "Gli spaghetti alla carbonara sono un piatto tradizionale della cucina romana. Gli ingredienti principali includono uova, pecorino romano, pancetta o guanciale e pepe nero. La cremosità della salsa è data dalla miscelazione delle uova con il calore degli spaghetti appena scolati."
      },
      {
          id: 2,
          name: "Tacos al Pastor",
          shortDescription: "Deliziosi tacos messicani con carne di maiale marinata.",
          longDescription: "I Tacos al Pastor sono un piatto iconico della cucina messicana, noto per la carne di maiale marinata con spezie e ananas. La carne viene cotta su uno spiedo verticale e servita su piccole tortillas di mais con cipolla, coriandolo, e una spruzzata di succo di lime."
      },
      {
          id: 3,
          name: "Sushi Nigiri",
          shortDescription: "Sushi giapponese con pesce fresco su riso pressato.",
          longDescription: "Il Sushi Nigiri è una delle forme più semplici e raffinate di sushi. Consiste in una piccola porzione di riso condito con aceto di riso, pressato a mano e sormontato da una fetta sottile di pesce fresco come salmone, tonno o gamberi. Viene solitamente servito con salsa di soia, wasabi e zenzero marinato."
      }
    ],
    piatto: null
  };

  handleDetails(dish) {
    this.setState({ screen: false, piatto: dish });
  }

  handleBack() {
    this.setState({ screen: true, piatto: null });
  }

  handleDelete() { 
    console.log("Deleting " + this.state.piatto.id);
    let newDishes = this.state.dishes.filter(c => c.id !== this.state.piatto.id);
    this.setState({ dishes: newDishes });
    console.log(this.state);
    this.handleBack();
  }

  render() { 
        if(this.state.screen){
          return (
          <View style={styles.container}>
            <ListScreen
            dishes={this.state.dishes}
            handleDetails={(dish) => this.handleDetails(dish)}
          />
          </View>
          );
        }else{
          return (
          <View style={styles.container}>
          <DetailsScreen
            piatto={this.state.piatto}
            handleBack={() => this.handleBack()}
            handleDelete={() => this.handleDelete()}
          />
          </View>
          );
        }
  } 
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
});

export default App;
