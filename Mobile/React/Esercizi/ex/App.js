import React, { useState, useEffect } from 'react';
import { View, Text } from 'react-native';
import UserCard from './UserInfo';
import { fetchData } from './viewModel/AppViewModel';

const App = () => {
  const [userInfo, setUserInfo] = useState({
    firstName: '',
    lastName: '',
    cardFullName: '',
    cardNumber: '',
    cardExpireMonth: '',
    cardExpireYear: '',
    cardCVV: ''
  });

  useEffect(() => {
    const loadUserData = async () => {
      const data = await fetchData();
      if (data) {
        setUserInfo(data);
      } else {
        console.log("Failed to fetch user data");
      }
    };
    loadUserData();
  }, []);

  return (
    <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
      <Text>User Information</Text>
      <UserCard userInfo={userInfo} setUserInfo={setUserInfo} />
    </View>
  );
};

export default App;

