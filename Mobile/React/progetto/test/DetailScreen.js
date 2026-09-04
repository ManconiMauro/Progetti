import React from 'react';
import { View, Text, TouchableOpacity, Image } from 'react-native';
import commonStyles from './style/StyleSheet'; 

class DetailScreen extends React.Component {
  render() {
    console.log(this.props.piatto);

    return (
    <View style={commonStyles.container}>
      <View style={commonStyles.dishContainer}>
        <Text style={commonStyles.title}>{this.props.piatto.name}</Text>
        <View style={{ alignItems: 'center' }}>
        <Image
        source={{ uri: `data:image/png;base64,${this.props.piatto.base64}` }}
        style={commonStyles.image}
        />
        </View>
        <Text style={commonStyles.description}>{this.props.piatto.longDescription}</Text>

        <TouchableOpacity style={commonStyles.button} onPress={() => this.props.handleBack()}>
          <Text style={commonStyles.buttonText}>Indietro</Text>
        </TouchableOpacity>
      </View>
    </View>
    );
  }
}

export default DetailScreen;