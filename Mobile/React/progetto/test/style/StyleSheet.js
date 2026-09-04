import { StyleSheet } from 'react-native'; 
const commonStyles = StyleSheet.create({ 
      appContainer: {
        flex: 1,
      },
      title: {
        fontSize: 24,
        fontWeight: 'bold',
        marginBottom: 20,
      },
      screenContainer: {
        flex: 7,
        backgroundColor: '#4A4A4A',
        paddingTop: 40,
        justifyContent: 'center',
        alignItems: 'center',
      },
      navBar: {
        flex: 1,
        flexDirection: 'row',
        justifyContent: 'space-evenly',
        backgroundColor: '#1F1F1F',
        padding: 10,
      },
      navButton: {
        backgroundColor: '#1F1F1F',
        padding: 10,
        borderRadius: 10,
        textAlignVertical: 'center',
        flex: 1,
        marginHorizontal: 5,
      },
      navButtonText: {
        textAlign: 'center',
        color: '#fff',
        fontWeight: 'bold',
      },
      homeContainer: {
        flex: 1,
        backgroundColor: '#4A4A4A',
        paddingTop: 20,
        justifyContent: 'center',
        alignItems: 'center',
      },
      container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#4A4A4A',
      },
      itemContainer: {
        backgroundColor: '#333',
        borderRadius: 10,
        padding: 15,
        marginVertical: 8,
      },
      name: {
        fontSize: 18,
        fontWeight: 'bold',
      },
      description: {
        fontSize: 14,
        color: '#666',
        marginBottom: 10,
      },
      button: {
        backgroundColor: '#D96C3C',
        margin:10,
        padding: 15,
        borderRadius: 10,
      },
      buttonText: {
        color: '#fff',
        fontWeight: 'bold',
        textAlign: 'center',
      },
      dishContainer: {
        padding: 10,
        marginBottom: 10,
        backgroundColor: '#F5F5DC',
        borderRadius: 10,
        borderColor: '#ddd',
      },
      profileContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#4A4A4A',
      },
      userCard: {
        width: '85%',
        padding: 20,
        backgroundColor: '#F5F5DC',
        borderRadius: 15,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.2,
        shadowRadius: 5,
      },
      label: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#666',
        marginTop: 10,
      },
      input: {
        borderWidth: 1,
        borderColor: '#aaa',
        padding: 10,
        borderRadius: 10,
        marginTop: 5,
        color: '#333',
        backgroundColor: '#fff',
      },
      listContainer: {
        flex: 1, // Assicura che il contenitore occupi tutto lo spazio disponibile
      },
      flatListContent: {
        paddingBottom: 16, // Aggiunge spazio in fondo per evitare che gli ultimi elementi siano troppo vicini
      },
      image: {
        width: 200,
        height: 200,
      },
}); 

export default commonStyles; 