import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View } from 'react-native';
import { useEffect, useState } from 'react';
import AppViewModel, { fetchData } from './viewModel/AppViewModel';

export default function App() {
  const [deliveryText, setDeliveryText] = useState("Loding data...")

  useEffect (() => {
    console.log("Component loaded for the first time")
    fetchData(2).then((data)=>{
      setDeliveryText(data)
    })
  }, [])

  return (
    <View style={styles.container}>
      <Text>{deliveryText}</Text>
      <StatusBar style="auto" />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
