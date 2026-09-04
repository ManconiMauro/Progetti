import React from 'react';
import { View, Text, TouchableOpacity, Image } from 'react-native';
import commonStyles from './style/StyleSheet'; 

class Dish extends React.Component { 
  render() {
    return (
      <View style={commonStyles.dishContainer}>
        <TouchableOpacity
          style={commonStyles.button}
          onPress={() => this.props.handleDetails(this.props.data)}
        >
          <Text style={commonStyles.name}>{this.props.data.name}</Text>
            <View style={{ alignItems: 'center' }}>s
                <Image
                source={{ uri: `data:image/png;base64,${this.props.data.base64}` }}
                style={commonStyles.image}
                />
            </View>
        <Text style={commonStyles.description}>{this.props.data.shortDescription}</Text>
        </TouchableOpacity>
      </View>
    );
  }
}

export default Dish;