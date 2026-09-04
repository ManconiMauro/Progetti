import AsyncStorage from '@react-native-async-storage/async-storage';

export default class CommunicationController {
    static BASE_URL = "https://develop.ewlab.di.unimi.it/mc/2425/";

    static async getSid() {
        const sid = await AsyncStorage.getItem('sid');
        if (!sid) throw new Error('SID non trovato in AsyncStorage');
        return sid.replace(/(^"|"$)/g, '');
    }

    static async getUid() {
        const uid = await AsyncStorage.getItem('uid');
        if (!uid) throw new Error('UID non trovato in AsyncStorage');
        return uid.replace(/(^"|"$)/g, '');
    }

    static async genericRequest(endpoint, verb, queryParams, bodyParams) {
        const queryParamsFormatted = new URLSearchParams(queryParams).toString();
        const url = this.BASE_URL + endpoint + "?" + queryParamsFormatted;
        console.log("sending " + verb + " request to: " + url);
        let fetchData = {
            method: verb,
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
        };
        if (verb !== 'GET') {
            fetchData.body = JSON.stringify(bodyParams);
        }
        let httpResponse = await fetch(url, fetchData);

        const status = httpResponse.status;
        if (status === 200) {
            let deserializedObject = await httpResponse.json();
            return deserializedObject;
        } else {
            const message = await httpResponse.text();
            let error = new Error(
                "Error message from the server. HTTP status: " + status + " " + message
            );
            throw error;
        }
    }

    static async genericGetRequest(endpoint, queryParams, bodyParams) {
        return await this.genericRequest(endpoint, 'GET', queryParams, bodyParams);
    }

    static async genericPostRequest(endpoint, queryParams, bodyParams) {
        return await this.genericRequest(endpoint, 'POST', queryParams, bodyParams);
    }

    static async createUser() {
        let endpoint = "user";
        let queryParams = "";
        let bodyParams = {};
        console.log(
            "Create new User called with endpoint: ",
            endpoint,
            " and queryParams: ",
            queryParams,
            " and bodyParams:",
            bodyParams
        );
        return await this.genericPostRequest(endpoint, queryParams, bodyParams);
    }

    static async getMenus(lat, lng) {
        let endpoint = "menu";
        const sid = await this.getSid();
        let queryParams = { lat: lat, lng: lng, sid: sid };
        let bodyParams = {};
        console.log(
            "Get menu called with endpoint: ",
            endpoint,
            " and queryParams: ",
            queryParams,
            " and bodyParams:",
            bodyParams
        );
        return await this.genericGetRequest(endpoint, queryParams, bodyParams);
    }

    static async getMenuDetails(mid, lat, lng){
        let endpoint = "menu/"+mid;
        const sid = await this.getSid();
        let queryParams = { lat: lat, lng: lng, sid: sid };
        let bodyParams = {};
        console.log(
            "Get menu called with endpoint: ",
            endpoint,
            " and queryParams: ",
            queryParams,
            " and bodyParams:",
            bodyParams
        );
        return await this.genericGetRequest(endpoint, queryParams, bodyParams);
    }

    static async getMenuImg(mid) {
        let endpoint = "menu/" + mid + "/image";
        const sid = await this.getSid(); 
        let queryParams = { sid: sid };
        let bodyParams = {};
        console.log(
            "Get menu Image called with endpoint: ",
            endpoint,
            " and queryParams: ",
            queryParams,
            " and bodyParams:",
            bodyParams
        );
        return await this.genericGetRequest(endpoint, queryParams, bodyParams);
    }
}
