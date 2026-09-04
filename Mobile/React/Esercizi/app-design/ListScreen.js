import React from 'react';
import { View, FlatList, StyleSheet } from 'react-native';
import Dish from './Dish';
import commonStyles from './style/StyleSheet'; 

class ListScreen extends React.Component { 
  render() {
    return (
      <View style={commonStyles.container}>
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

export default ListScreen;
