import React from 'react';
import { View, FlatList } from 'react-native';
import Dish from './Dish';
import commonStyles from './style/StyleSheet';

class ListScreen extends React.Component {
  render() {
    return (
      <View style={[commonStyles.listContainer]}>
        <FlatList
          data={this.props.dishes}
          renderItem={({ item }) => (
            <Dish
              key={item.id}
              data={item}
              handleDetails={() => this.props.handleDetails(item)}
            />
          )}
          keyExtractor={(item) => item.mid.toString()}
          contentContainerStyle={commonStyles.flatListContent} // Stile aggiuntivo per padding
        />
      </View>
    );
  }
}

export default ListScreen;

