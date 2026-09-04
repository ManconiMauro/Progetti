import { StyleSheet } from "react-native";

const commonStyles = StyleSheet.create({
    appContainer: {
        flex: 1,
    },
    screenContainer: {
      flex: 7,
      backgroundColor: '#F8F8F8', // Sfondo chiaro
      paddingTop: 40,
      justifyContent: 'center',
      alignItems: 'center',
    },
    navBar: {
      flexDirection: 'row',
      justifyContent: 'space-around',
      backgroundColor: '#F8F8F8', // Sfondo chiaro per la nav bar
      paddingVertical: 10,
      borderTopWidth: 1,
      borderTopColor: '#ddd',
      elevation: 5,
      shadowColor: '#000',
      shadowOffset: { width: 0, height: -2 },
      shadowOpacity: 0.1,
      shadowRadius: 4,
    },
    tabBarLabelStyle: {
      fontSize: 12,
      color: '#333',
    },
    tabBarIconStyle: {
      color: '#666',
    },
    tabBarActiveTintColor: '#FFC107',
    tabBarInactiveTintColor: '#666',
    headerStyle: {
      backgroundColor: '#F8F8F8',
      height: 80,
    },
    headerTitleStyle: {
      fontWeight: 'bold',
      fontSize: 20,
    },
    headerTintColor: '#333',
    container: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center',
      backgroundColor: '#F8F8F8',
    },
    listContainer: {
      flex: 1,
      backgroundColor: '#F8F8F8',
      paddingHorizontal: 10,
    },
    flatListContent: {
      paddingVertical: 10,
    },
    dishContainer: {
      backgroundColor: '#F5E1C2',
      borderRadius: 12,
      padding: 12,
      marginBottom: 12,
      shadowColor: '#000',
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.2,
      shadowRadius: 4,
      elevation: 4,
    },
    detailContainer: {
      backgroundColor: '#F5E1C2', // Beige caldo per il dettaglio
      borderRadius: 12,
      padding: 16,
      margin: 16,
      shadowColor: '#000',
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.2,
      shadowRadius: 4,
      elevation: 4,
    },
    name: {
      fontSize: 18,
      fontWeight: 'bold',
      color: '#222',
      marginBottom: 6,
    },
    imagePriceContainer: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      marginBottom: 8,
    },
    image: {
      width: 120,
      height: 100,
      resizeMode: 'cover',
      borderRadius: 8,
    },
    price: {
      fontSize: 16,
      fontWeight: 'bold',
      color: '#28A745',
    },
    description: {
      fontSize: 14,
      color: '#444',
      textAlign: 'justify',
    },
    button: {
      backgroundColor: '#6B4EFF',
      paddingVertical: 12,
      borderRadius: 10,
      alignItems: 'center',
      marginBottom: 10,
    },
    buttonText: {
      color: '#FFF',
      fontSize: 16,
      fontWeight: 'bold',
    },
    profileContainer: {
      flex: 1,
      backgroundColor: '#F8F8F8',
      padding: 20,
    },
    userCard: {
      backgroundColor: '#FFFFFF',
      padding: 20,
      borderRadius: 12,
      shadowColor: '#000',
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.1,
      shadowRadius: 4,
      elevation: 3,
    },
    dateContainer: {
      flexDirection: 'row',
      justifyContent: 'space-between',
    },
    halfInput: {
      flex: 1,
      borderWidth: 1,
      borderColor: '#D1D1D1',
      paddingVertical: 12,
      paddingHorizontal: 10,
      borderRadius: 8,
      backgroundColor: '#FFF',
      fontSize: 16,
      color: '#333',
      marginRight: 10, // Spazio tra MM e YY
    },
    label: {
      fontSize: 16,
      fontWeight: 'bold',
      color: '#333',
      marginBottom: 5,
      marginTop: 10,
    },
    input: {
      borderWidth: 1,
      borderColor: '#D1D1D1',
      paddingVertical: 12,
      paddingHorizontal: 10,
      borderRadius: 8,
      backgroundColor: '#FFF',
      fontSize: 16,
      color: '#333',
    },
    mapContainer: {
      flex: 1,
      backgroundColor: '#F8F8F8',
    },
    map: {
      width: '100%',
      height: '100%',
    },
    mapMarkerImage: {
      width: 25, // Ridotto da 40
      height: 25, // Ridotto da 40
      resizeMode: 'contain', // Mantiene proporzioni senza distorsioni
    },
    mapOverlay: {
      position: 'absolute',
      top: 10,
      alignSelf: 'center',
      backgroundColor: 'rgba(255, 255, 255, 0.9)',
      padding: 8,
      borderRadius: 8,
      shadowColor: '#000',
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.1,
      shadowRadius: 4,
    },
});

export default commonStyles;