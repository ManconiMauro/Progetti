import React from 'react';
import { View, FlatList, StyleSheet } from 'react-native';
import Dish from './Dish';

class ListScreen extends React.Component { 
  render() {
    return (
      <View style={styles.container}>
        <FlatList
          data={this.props.dishes}
          renderItem={({ item }) => (
            <Dish
              key={item.id}
              data={item}
              handleDetails={() => this.props.handleDetails(item)}
            />
          )}
          keyExtractor={item => item.id.toString()}
        />
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 10,
  },
});

export default ListScreen;
